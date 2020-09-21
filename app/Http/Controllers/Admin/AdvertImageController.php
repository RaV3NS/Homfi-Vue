<?php

namespace App\Http\Controllers\Admin;

use App\Advert;
use App\AdvertImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvertImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Advert $advert)
    {
        $advert->addAllMediaFromRequest()->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('images');
        });

        $oldImages = $advert->getMedia('images');

        if($deletedImages = $request->get('deleted_image')) {
            foreach($oldImages as $oldImage) {
                if(in_array($oldImage->id, $deletedImages)) {
                    $oldImage->delete();
                }
            }
        }

        foreach($request->get('degrees') as $imageId => $degree) {
            if(!empty($degree)) {
                $degree = $this->normalizeDegree($degree);

                foreach($oldImages as $oldImage) {
                    if($oldImage->id == $imageId) {
                        $oldImage->manipulations = [
                            '*' => ['orientation' => $degree],
                        ];
                        $oldImage->save();
                    }
                }
            }
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdvertImage  $advertImage
     * @return \Illuminate\Http\Response
     */
    public function show(AdvertImage $advertImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdvertImage  $advertImage
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvertImage $advertImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvertImage  $advertImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvertImage $advertImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvertImage  $advertImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertImage $advertImage)
    {
        //
    }

    protected function normalizeDegree($degree)
    {
        $fraction = $degree/360;
        if($degree > 0) {
            $koef = fmod($fraction, 1);
            $degree = round($koef * 360);
        } else {
            $degree = round(360 * ceil($fraction) + $degree);
        }

        return $degree;
    }
}
