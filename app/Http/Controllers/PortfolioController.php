<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Portfolio;
use App\PortfolioFile;
use Intervention\Image\Facades\Image;

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
                $file_loc = public_path('/images/portfolios/'.$filename);
                if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                    $upload = Image::make($file)->resize(380, 320, function($constraint){
                        $constraint->aspectRatio();
                    });
                    $upload->sharpen(8)->save($file_loc);
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

    public function delFile($id){
        $file = PortfolioFile::findOrFail($id);
        $filepath = public_path('/images/portfolios/'.$file);
        if(file_exists($filepath)){
            unlink($filepath);
        }
        $file->delete();

        return response()->json(['message' => 'Deleted'], 201);
    }
}
