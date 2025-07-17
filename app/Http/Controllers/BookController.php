<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Statut;
use Illuminate\Http\Request;

class BookController extends Controller
{
    
    public function index() {
        $books = Book::all() ;
        return view('books.index', compact('books')) ;
    }

    //Methode create et store
    public function create() {
        $authors = Author::all() ; //Récupération de tous les auteurs
        $statuts = Statut::all() ;
        //dd($authors) ;
        
        // Recupération de la liste des auteurs
        return view('books.create', compact(['authors', 'statuts']));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'author_id' => 'required',
            'annee' => 'required|integer|min:1000|max:' . date('Y'),
            'statuts_id' => 'required',
            'note' => 'nullable|string'
        ]);
        
    $dataSave = $request->all() ; // Toutes les données qui sont dans $request sont stockés dans $dataSave

    // Début de la gestion de l'upload de l'image

        //Vérification de l'existence du fichier 
            if ($request->hasFile('image')) {       // Vérfication si l'image est bien un fichier
                
                // Préparation de l'image 
                $image = $request->file('image') ;

                // Changer le nom de l'image
                $imageName = time() . '_' . $image->getClientOriginalName() ;

                // Enregistrement de l'image sur le serveur
                $image->storeAs('books' , $imageName) ;      

                // Enregistrement du nom de l'image
                $dataSave['image'] = $imageName ; 

            }

        Book::create( $validated) ; // Création 


        // Redirection
        return redirect()->route('books.index')
            ->with('success', 'Livre ajouté avec succès !');
    }

    public function show(Book $book){
        return view('books.show', compact('book'));
    }

    public function edit(Book $book){
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book){
        $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'annee' => 'required|integer|min:1000|max:' . date('Y'),
            'statuts_id' => 'required',
            'note' => 'nullable|string'

        ]);

        $book->update([
            'titre' => $request->titre,
            'auteur' => $request->auteur,
            'annee' => $request->annee,
            'statut' => $request->statut,
            'favori' => $request->has('favori'),
            'note' => $request->note
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Livre modifié avec succès !');
    }

    public function destroy(Book $book){
        $book->delete();
    
        return redirect()->route('books.index')
            ->with('success', 'Livre supprimé avec succès !');
    }   
}


