<?php

namespace App\Models;

use App\Models\Author;
use App\Models\Statut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    //
    use HasFactory ; 

    protected $fillable = [
        'titre',
        'author_id',
        'annee',
        'statuts_id',
        'favori',
        'note',
        'image'
    ];

    protected $casts =[
        'favori' => 'boolean',
    ];

    /**
     * Get the author that owns the Book
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the statuts that owns the Book
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statut(): BelongsTo
    {
        return $this->belongsTo(Statut::class, 'statuts_id');
    }

    // Acesseur pour l'URL complète de l'image
    public function getImageUrlAttribute (){
        return $this->image ? asset('storage/books/' .$this->image) : null ;
    }
}
