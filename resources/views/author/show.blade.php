@extends('layouts.app')

@section('titre', 'Livres de ' . $author->name)

@section('content')
<div class="row">
    <div class="col-12">
        <!-- En-tête avec informations de l'auteur -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">
                            <i class="fas fa-user me-2"></i>
                            {{ $author->name }}
                        </h1>
                        <p class="text-muted mb-0">
                            {{ $author->books->count() }} livre(s) dans votre bibliothèque
                        </p>
                    </div>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour à la bibliothèque
                    </a>
                </div>
            </div>
        </div>

        @if($author->books->isEmpty())
            <!-- Message si aucun livre -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-book-open fa-3x text-muted"></i>
                </div>
                <h4 class="text-muted">Aucun livre trouvé</h4>
                <p class="text-muted">Vous n'avez encore aucun livre de cet auteur dans votre bibliothèque.</p>
                <a href="{{ route('books.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Ajouter un livre
                </a>
            </div>
        @else
            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $author->books->where('statut', 'lu')->count() }}</h5>
                            <p class="card-text small">Livres lus</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $author->books->where('statut', 'en cours')->count() }}</h5>
                            <p class="card-text small">En cours</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $author->books->where('statut', 'à lire')->count() }}</h5>
                            <p class="card-text small">À lire</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $author->books->where('favori', true)->count() }}</h5>
                            <p class="card-text small">Favoris</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des livres -->
            <div class="row">
                @foreach ($author->books as $book)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        {{ $book->titre }}
                                        @if($book->favori)
                                            <i class="fas fa-heart text-danger ms-1"></i>
                                        @endif
                                    </h5>
                                    <span class="badge 
                                        @if($book->statut == 'lu') bg-success
                                        @elseif($book->statut == 'en cours') bg-warning
                                        @else bg-info
                                        @endif">
                                        {{ ucfirst($book->statut) }}
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $book->annee }}
                                </p>

                                @if($book->note)
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ Str::limit($book->note, 100) }}
                                        </small>
                                    </p>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('books.show', $book) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    
                                    <form action="{{ route('books.destroy', $book) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination si nécessaire -->
            @if($author->books instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $author->books->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endsection