<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use App\Models\matakuliah;
use App\Models\prodi;
use App\Models\User; // import model User
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;




class mahasiswaController extends Controller
{

    public function __construct(Request $request) //
    {
        //

        $this->request = $request;
    }

    protected function jwt(mahasiswa $mahasiswa)
    {
        $payload = [
            'iss' => 'lumen-jwt', //issuer of the token
            'sub' => $mahasiswa->nim, //subject of the token
            'iat' => time(), //time when JWT was issued.
            'exp' => time() + 60 * 60 //time when JWT will expire
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    // Tiga Fungsi
    public function defaultUser()
    {
        $mahasiswa = mahasiswa::create([
            'nim' => '205150707111000',
            'nama' => 'dimas',
            'angkatan' => '2020',
            'password' => 'dimas123'
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'default user created',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function createUser(Request $request)
    {
        $nim = $request->nim;
        $nama = $request->nama;
        $angkatan = $request->angkatan;
        $password = $request->password;

        $mahasiswa = mahasiswa::create([
            'nim' => $nim,
            'nama' => $nama,
            'angkatan' => $angkatan,
            'password' => $password
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'new user created',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $nim = $request->nim;
        $nama = $request->nama;
        $angkatan = $request->angkatan;
        $password = Hash::make($request->password);

        $mahasiswa = mahasiswa::create([
            'nim' => $nim,
            'nama' => $nama,
            'angkatan' => $angkatan,
            'password' => $password
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'new user created',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function getUsers()
    {
        $mahasiswa = mahasiswa::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all users grabbed',
            'mahasiswa' => $mahasiswa,
        ], 200);
    }

    public function getUserByToken(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'grabbed user by token',
            'mahasiswa' => $request->mahasiswa,
        ], 200);
    }
    // Tiga Fungsi

    //TUBES
    public function login(Request $request)
    {
        $nim = $request->nim;
        $password = $request->password;

        $mahasiswa = mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'Error',
                'message' => 'user not exist',
            ], 404);
        }

        if (!Hash::check($password, $mahasiswa->password)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'wrong password',
            ], 400);
        }

        $mahasiswa->token = $this->jwt($mahasiswa); //
        $mahasiswa->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'successfully login',
            'data' => [
                'mahasiswa' => $mahasiswa,
            ]
        ], 200);
    }

    public function addprodi(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;

        $prodi = prodi::create([
            'id' => $id,
            'nama' => $nama,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'new prodi created',
            'data' => [
                'prodi' => $prodi,
            ]
        ], 200);
    }

    public function getProdi()
    {
        $prodi = prodi::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all prodi grabbed',
            'prodi' => $prodi,
        ], 200);
    }

    public function addmatkul(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;

        $matakuliah = matakuliah::create([
            'id' => $id,
            'nama' => $nama,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'new matkul created',
            'data' => [
                'matakuliah' => $matakuliah,
            ]
        ], 200);
    }

    public function getMatkul()
    {
        $matakuliah = matakuliah::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all matkul grabbed',
            'matakuliah' => $matakuliah,
        ], 200);
    }

    public function getMahasiswaById(Request $request)
    {
        $mahasiswa = mahasiswa::find($request->nim);

        return response()->json([
            'success' => true,
            'message' => 'All post grabbed',
            'mahasiswa' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'angkatan' => $mahasiswa->angkatan,
                'password' => $mahasiswa->password,
                'matakuliah' => $mahasiswa->matakuliahs,
            ]
        ]);
    }

    public function addMatkulMahasiswa($nim, $mkId)
    {
        $mahasiswa = mahasiswa::find($nim);
        $mahasiswa->matakuliahs()->attach($mkId);
        return response()->json([
            'success' => true,
            'message' => 'matkul added to mahasiswa',
        ]);
    }

    public function delMatkulMahasiswa($nim, $mkId)
    {
        $mahasiswa = mahasiswa::find($nim);
        $mahasiswa->matakuliahs()->detach($mkId);
        return response()->json([
            'success' => true,
            'message' => 'matkul Deleted from mahasiswa',
        ]);
    }

    public function getByNim($nim)
    {
        $mahasiswa = Mahasiswa::with('matakuliah', 'prodi')->find($nim);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil ditampilkan',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    //TUBES
}
