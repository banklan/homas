<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Portfolio;
use App\PortfolioFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function createPortFolio(Request $request, $id){
        // check if 3 portfolios are created already
        $count = Portfolio::where('service_id', $id)->count();
        if($count < 3){
            $user = auth('api')->user()->id;
            $service_id = $id;

            $this->validate($request, [
                'portfolio.title' => 'required|min:5|max:200',
                'portfolio.detail' => 'required|min:10|max:600',
                'portfolio.cost' => 'numeric|between:1,100000000000',
            ]);

            $portfolio = new Portfolio;
            $portfolio->service_id = $service_id;
            $portfolio->title = $request->portfolio['title'];
            $portfolio->detail = $request->portfolio['detail'];
            $portfolio->cost = $request->portfolio['cost'] * 100;
            $portfolio->is_approved = false;
            $portfolio->save();

            return response()->json($portfolio, 201);
        }
    }

    public function uploadFiles(Request $request, $id){
        $files = $request->files;
        if($request->hasFile('files')){
            foreach($request->file('files') as $file){
                $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
                $ext = $file->getClientOriginalExtension();
                $filename = substr(str_shuffle($pool), 0, 8).".".$ext;
                    // save new file in folder
                // $file_loc = public_path('/images/portfolios/'.$filename);
                $file_loc = '/portfolios/' .$filename;
                if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                    $upload = Image::make($file)->resize(420, 350, function($constraint){
                        $constraint->aspectRatio(); })->sharpen(5);
                    $fixedImg = $upload->stream();
                    Storage::disk('s3')->put($file_loc, $fixedImg->__toString());
                }

                $pf_img = new PortfolioFile;
                $pf_img->portfolio_id = $id;
                $pf_img->file = $filename;
                $pf_img->save();
            }
            return response()->json(['message' => 'Files saved'], 200);
        }
    }

    public function getPortfolioFiles($id){
        $files = PortfolioFile::where('portfolio_id', $id)->get();
        foreach($files as $file){
            $pf_file = $file->file;
            $path = 'portfolios/' .$pf_file;
            $fileUrl = Storage::disk('s3')->url($path);
            $file->url = $fileUrl;
        }

        return response()->json($files, 200);
    }

    public function getPortfolio($id){
        $portfolio = Portfolio::with('service')->findOrFail($id);

        return response()->json($portfolio, 200);
    }

    public function getOtherPortfolios($id){
        $pf = Portfolio::findOrFail($id);
        $service_id = $pf->service_id;
        $pfs = Portfolio::where('service_id', $service_id)->where('id', '!=', $id)->get();

        return response()->json($pfs, 200);
    }

    public function updatePf(Request $request, $id){
        $pf = Portfolio::findOrFail($id);
        $this->validate($request, [
            'portfolio.title' => 'required|min:5|max:200',
            'portfolio.detail' => 'required|min:10|max:600',
            'portfolio.cost' => 'numeric|between:1,100000000000',
        ]);
        $pf->update([
            $pf->title =  $request->portfolio['title'],
            $pf->detail =  $request->portfolio['detail'],
            $pf->cost =  $request->portfolio['cost'] * 100,
        ]);

        return response()->json($pf, 200);
    }

    public function delPfFile($file){
        // $pf_file = PortfolioFile::findOrFail($id);
        $pf_file = PortfolioFile::where('file', $file)->first();
        // $file = $pf_file->file;
        // delete from s3
        $path = 'portfolios/' .$file;
        if(file_exists($path)){
            Storage::disk('s3')->delete($path);
        }
        $pf_file->delete();

        return response()->json(['message' => 'portfolio file Deleted'], 201);
    }

    public function deletePortfolio($id){
        $pf = Portfolio::findOrFail($id);
        // get files in s3 and delete
        $files = PortfolioFile::where('portfolio_id', $id)->get();
        foreach($files as $file){
            $pffile = $file->file;
            $path = 'portfolios/' .$pffile;
            if($path){
                Storage::disk('s3')->delete($path);
            }
            $file->delete();
        }
        // delete pf from db
        $pf->delete();
        return response()->json(['message' => 'Portfolio deleted!'], 200);
    }

    public function getPfFilesFromS3($id){
        $files = PortfolioFile::where('portfolio_id', $id)->get();
        $pf_files = [];
        foreach ($files as $file){
            $pf_file = $file->file;
            $path = 'portfolios/' .$pf_file;
            $fileUrl = Storage::disk('s3')->url($path);
            $pf_files[] = $fileUrl;
        }
        return response()->json($pf_files, 200);
    }
}
