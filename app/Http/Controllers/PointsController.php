<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PointsController extends Controller
{
    public $points;

    public function __construct()
    {
        $this->points = new pointsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $request->validate(
            [
                'geometry_point' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255', // ← wajib diisi
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_point.required' => 'Geometry point harus diisi.',
                'name.required' => 'Nama harus diisi.',
                'name.string' => 'Nama harus berupa string.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                // pesan validasi description
                'description.required' => 'Deskripsi harus diisi.',
                'description.string' => 'Deskripsi harus berupa string.',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
                'image.image' => 'Image harus berupa gambar.',
                'image.mimes' => 'Image harus berupa file JPEG, PNG, atau JPG.',
                'image.max' => 'Image tidak boleh lebih dari 2MB.',
            ]
        );

        // Create directory in public storage if not exists
        $publicImagePath = public_path('storage/images');
        if (!is_dir($publicImagePath)) {
            mkdir($publicImagePath, 0777, true);
        }

        // Get image from request and move to public/storage/images
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move($publicImagePath, $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // simpan data ke database
        if (!$this->points->create($data)) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menyimpan data point.');
        }

        // kembali ke halaman peta
        return redirect()->route('peta')
            ->with('success', 'Data point berhasil disimpan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Point',
            'id' => $id,
            'point' => $this->points->find($id),
        ];

        return view('map-edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validasi input
        $request->validate(
            [
                'geometry' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255', // ← wajib diisi
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry.required' => 'Geometry harus diisi.',
                'name.required' => 'Nama harus diisi.',
                'name.string' => 'Nama harus berupa string.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                // pesan validasi description
                'description.required' => 'Deskripsi harus diisi.',
                'description.string' => 'Deskripsi harus berupa string.',
                'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
                'image.image' => 'Image harus berupa gambar.',
                'image.mimes' => 'Image harus berupa file JPEG, PNG, atau JPG.',
                'image.max' => 'Image tidak boleh lebih dari 2MB.',
            ]
        );

        // Create directory in public storage if not exists
        $publicImagePath = public_path('storage/images');
        if (!is_dir($publicImagePath)) {
            mkdir($publicImagePath, 0777, true);
        }

        $image_old = $this->points->find($id)->image;

        // Get image from request; if new image uploaded, move and delete old image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move($publicImagePath, $name_image);

            // delete old image if exists
            if ($image_old != null) {
                $oldPath = public_path('storage/images/' . $image_old);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        } else {
            $name_image = $image_old; // tetap gunakan gambar lama jika tidak ada gambar baru yang diunggah
        }

        $data = [
            'geom' => $request->geometry,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // simpan update ke database
        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('peta')
                ->with('error', 'Gagal memperbarui data point.');
        }

        // kembali ke halaman peta
        return redirect()->route('peta')
            ->with('success', 'Data point berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mencari data berdasarkan ID
        $point = $this->points->find($id);

        // Hapus file gambar jika ada di storage
        if ($point->image) {
            $filepath = public_path('storage/images/' . $point->image);
            if (File::exists($filepath)) {
                File::delete($filepath);
            }
        }

        // hapus data dari database
        if (!$point->delete()) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data point.');
        }

        // kembali ke halaman peta dengan pesan sukses
        return redirect()->route('peta')
            ->with('success', 'Data point berhasil dihapus.');
    }
}
