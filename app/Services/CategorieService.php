<?php

namespace App\Services;

use App\Models\Categorie;

class CategorieService
{
    public function getAll()
    {
        return Categorie::all();
    }

    public function getById(int $id)
    {
        return Categorie::findOrFail($id);
    }

    public function create(array $data)
    {
        return Categorie::create($data);
    }

    public function update(int $id, array $data)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->update($data);
        return $categorie;
    }

    public function delete(int $id)
    {
        return Categorie::destroy($id);
    }

    public function getByType(string $type)
    {
        return Categorie::where('type', $type)->get();
    }

}
