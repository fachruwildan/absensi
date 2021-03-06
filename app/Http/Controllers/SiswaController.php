<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Http\Requests\SiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
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
    public function create(Kelas $kelas)
    {
        return view('siswa.create',[
            'kelas' => $kelas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiswaRequest $request, Kelas $kelas)
    {
        $schema = $request->all() + [
            'kelas_id' => $kelas->id,
            'point_pelanggaran' => 0,
            'point_penghargaan' => 0
        ];
        
        $siswa = new Siswa($schema);

        try{

            if($siswa->save()){
                return redirect()
                    ->route('siswa.show',[
                        'siswa' => $siswa,
                        'kelas' => $kelas
                    ])
                    ->with('success','Siswa berhasil ditambahkan');
            }else{
                return redirect()
                    ->route('kelas.show',[
                        'kelas' => $kelas
                    ])
                    ->with('error','Siswa gagal ditambahkan');
            }
        }catch(\Exception $e){
            return redirect()
                ->route('kelas.show',[
                    'kelas' => $kelas
                ])
                ->with('error','Siswa gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function show(Kelas $kelas, Siswa $siswa)
    {
        return view('siswa.show',[
            'kelas' => $kelas,
            'siswa' => $siswa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Kelas $kelas, Siswa $siswa)
    {
        return view('siswa.edit',[
            'siswa' => $siswa,
            'kelas' => $kelas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update(SiswaRequest $request, Kelas $kelas, Siswa $siswa)
    {
        if($siswa->update($request->all())){
            return redirect()->route('siswa.show',[
                'kelas' => $kelas,
                'siswa' => $siswa
            ])->with('success','Siswa berhasil diubah');
        }else{
            return redirect()->route('siswa.show',[
                'kelas' => $kelas,
                'siswa' => $siswa
            ])->with('error','Siswa gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kelas $kelas,Siswa $siswa)
    {
        try{
            if($siswa->delete()){
                return redirect()->route('kelas.show',['kelas' => $kelas])->with('success','Siswa berhasil dihapus');
            }else{
                return redirect()->route('kelas.show',['kelas' => $kelas])->with('error','Siswa gagal dihapus');
            }
        }catch(\Exception $e){
            return redirect()->route('kelas.show',['kelas' => $kelas])->with('error','Siswa gagal dihapus.');
        }
    }

    public function json(Kelas $kelas){
        $siswa = Siswa::where(['kelas_id' => $kelas->id])
                ->select(['nip','nama','point_pelanggaran','point_penghargaan','kelas_id'])
                ->get();

        $datatable = datatables()
            ->of($siswa)
            ->addColumn('action',function($data){
                $routeUpdate = route('siswa.edit',[
                        'kelas' => $data->kelas_id,
                        'siswa' => $data,
                    ]);
                $routeDetail = route('siswa.show',[
                        'kelas' => $data->kelas_id,
                        'siswa' => $data,
                    ]);
                $routeDestroy = route('siswa.destroy',[
                        'kelas' => $data->kelas_id,
                        'siswa' => $data,
                    ]);

                $token = csrf_token();
                $csrf = "<input type='hidden' value='$token' name='_token'>";
                $method = "<input type='hidden' value='DELETE' name='_method'>";
                
                $buttonUpdate = "<a href='$routeUpdate' class='btn btn-primary mb-1 mr-1'><i class='fa fa-pencil-alt'></i> Ubah</a>";
                $buttonDetail = "<a href='$routeDetail' class='btn btn-warning mb-1 mr-1'><i class='fa fa-eye'></i> Detail</a>";
                $buttonDestroy = "<form action='$routeDestroy' method='post' class='d-inline-block'> $csrf $method <button class='btn btn-danger mb-1 mr-1 deleteAlerts'><i class='fa fa-trash'></i> Hapus</button></form>";
                
                $html = "$buttonUpdate $buttonDetail $buttonDestroy";
                return $html;
            })
            ->make(true);

        return $datatable;

    }
}
