<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Multi Confection - Boutique en ligne</title>
  <script src="https://cdn.tailwindcss.com "></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
  <style>
    .account-dropdown {
      transition: all 0.2s ease-in-out;
      animation: fadeIn 0.2s ease-in forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Style des options de couleur */
    .color-option {
      width: 1.5rem;
      height: 1.5rem;
      border-radius: 999px;
      border: 1px solid #ccc;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .color-option:hover {
      transform: scale(1.1);
    }

    .color-option.selected {
      border: 2px solid #000;
      transform: scale(1.1);
    }

    /* Style du logo dans l'en-tête */
    header .logo {
      height: 4rem;
      width: auto;
    }

    @media (max-width: 728px) {
      header .logo {
        height: 4rem;
      }
    }

    /* Transition pour la modale produit */
    .product-modal {
      transition: all 0.3s ease;
    }

    /* Style des options de taille */
    .size-option {
      transition: background-color 0.2s, color 0.2s;
    }

    .size-option:hover,
    .size-option.selected {
      background-color: #3b82f6;
      color: white;
    }

    /* Style du dropdown du panier */
    .cart-dropdown {
      max-height: 70vh;
      overflow-y: auto;
    }

    /* Animation d'apparition des produits */
    @keyframes slideIn {
      from {
        transform: translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .animate-slideIn {
      animation: slideIn 0.3s ease-out forwards;
    }

    /* Style de l'image dans la modale */
    #modal-product-image {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: cover;
      border-radius: 0.5rem;
      transition: transform 0.3s ease;
    }

    #modal-product-image:hover {
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      #modal-product-image {
        max-height: 300px;
      }
    }

    /* Centrage vertical dans la modale produit */
    #product-modal .grid-cols-1.md\:grid-cols-2 {
      align-items: center;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

<?php
session_start();


// Connexion à la base
$host = 'localhost';
$dbname = 'boutique';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
// --- PAGINATION : Configuration ---
$produitsParPage = 8; // Nombre de produits par page (ajuste selon ton design)

// Récupérer la page actuelle depuis l'URL (ex: ?page=2)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Empêche les pages négatives

// Compter le nombre total de produits
$totalProduits = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();

// Calculer le nombre total de pages
$nombrePages = ceil($totalProduits / $produitsParPage);
$nombrePages = max(1, $nombrePages); // Au moins 1 page

// Calculer l'offset pour la requête SQL
$offset = ($page - 1) * $produitsParPage;

// Charger les produits de la page actuelle
$stmt = $pdo->prepare("SELECT * FROM produits ORDER BY date_ajout DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $produitsParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les produits (filtre optionnel par catégorie)
$stmt = $pdo->query("SELECT * FROM produits WHERE categorie = 'BTP/INDUSTRIE' ORDER BY date_ajout DESC");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header/Navigation -->
<header class="bg-white shadow-md sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex justify-between items-center">
    <div class="flex items-center space-x-4">
      <!-- Logo -->
      <a href="#" class="text-2xl font-bold text-indigo-600">
        <img src="images/Nouveau projet.png" alt="Multi Confection" class="logo h-10 w-auto">
      </a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">Accueil</a>
      <a href="#products" class="text-gray-700 hover:text-indigo-600">Produits</a>
      <a href="BTPINDUSTRIE.php" class="text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
      <a href="Restauration.php" class="text-gray-700 hover:text-indigo-600">Restauration</a>
      <a href="Sante.php" class="text-gray-700 hover:text-indigo-600">Santé</a>
      <a href="contact.php" class="text-gray-700 hover:text-indigo-600">Contact</a>
    </div>

    <div class="flex items-center space-x-4">
      <!-- Icône du compte avec menu déroulant -->
      <div class="relative inline-block text-left">
        <button id="account-btn" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
          <i class="fas fa-user text-xl"></i>
        </button>
        <div id="account-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
          <div class="py-1">
            <?php if (isset($_SESSION['user'])): ?>
              <a href="mon_compte.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mon compte</a>
              <a href="deconnexion.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Se déconnecter</a>
            <?php else: ?>
              <a href="connexion.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Se connecter</a>
              <a href="inscription.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">S'inscrire</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Panier -->
      <div class="relative">
        <button id="cart-btn" class="text-gray-700 hover:text-indigo-600 relative">
          <i class="fas fa-shopping-cart text-xl"></i>
          <span id="cart-count" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
        </button>
        <div id="cart-dropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg p-4 cart-dropdown">
          <div id="cart-items" class="space-y-3">
            <p class="text-gray-500 text-center py-4">Votre panier est vide</p>
          </div>
          <div class="border-t mt-3 pt-3">
            <div class="flex justify-between font-semibold">
              <span>Total:</span>
              <span id="cart-total">0 MAD</span>
            </div>
            <a href="commande.php" class="block mt-4 bg-indigo-600 text-white text-center py-2 rounded-md hover:bg-indigo-700">
    Passer la commande
</a>
          </div>
        </div>
      </div>

      <!-- Bouton menu mobile -->
      <button id="mobile-menu-btn" class="md:hidden text-gray-700">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden container mx-auto px-4 md:hidden">
  <div class="flex flex-col space-y-4 py-4">
    <a href="#" class="text-gray-700 hover:text-indigo-600">Accueil</a>
    <a href="#products" class="text-gray-700 hover:text-indigo-600">Produits</a>
    <a href="#" class="text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
    <a href="Restauration.php" class="text-gray-700 hover:text-indigo-600">Restauration</a>
    <a href="Sante.php" class="text-gray-700 hover:text-indigo-600">Santé</a>
    <a href="contact.php" class="text-gray-700 hover:text-indigo-600">Contact</a>
    <a href="connexion.php" class="text-gray-700 hover:text-indigo-600">Compte</a>
    <a href="inscription.php" class="text-gray-700 hover:text-indigo-600">Inscription</a>
  </div>
</div>

<!-- Script pour les menus déroulants -->
<script>
  
  
  if (accountBtn && accountDropdown) {
    accountBtn.addEventListener('click', (e) => {
      e.preventDefault();
      accountDropdown.classList.toggle('hidden');
    });

    // Fermer le menu si clic à l'extérieur
    document.addEventListener('click', (e) => {
      if (!accountDropdown.contains(e.target) && !accountBtn.contains(e.target)) {
        accountDropdown.classList.add('hidden');
      }
    });
  }

  
</script>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">Accueil</a>
                <a href="#products" class="py-2 text-gray-700 hover:text-indigo-600">Produits</a>
                <a href="BTPINDUSTRIE.php" class="py-2 text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
                <a href="Restauration.php" class="py-2 text-gray-700 hover:text-indigo-600">Restauration</a>
                <a href="Sante.php" class="py-2 text-gray-700 hover:text-indigo-600">Santé</a>
                <a href="contact.php" class="py-2 text-gray-700 hover:text-indigo-600">Contact</a>
                
            </div>
        </div>
    </header>

    <!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Votre Métier, Notre Priorité  Habillez-vous comme un Pro</h1>
            <p class="text-xl mb-6">Découvrez une large gamme de vêtements de travail robustes, confortables et stylés, conçus pour répondre aux exigences des vrais professionnels.</p>
            <a href="#products" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 inline-block">Voir les produits</a>
        </div>
        <div class="md:w-1/2 hero-image-container">
            <img src="images/JL6873M002.webp" class="hero-image">
        </div>
    </div>
</section>
    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Nos Catégories</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="BTPINDUSTRIE.php" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/tenues-pro-artisanat-btp-industrie.jpg" alt="BTP/INDUSTRIE" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">BTP/INDUSTRIE</h3>
                    </div>
                </a>
                <a href="Restauration.php" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/129.jpg" alt="Restauration" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Restauration</h3>
                    </div>
                </a>
                <a href="Sante.php" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/hopital-medical-sante.jpg" alt="Santé" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Santé</h3>
                    </div>
                </a>
                <a href="#" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Promotions" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Promotions</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Nos Produits</h2>
                <div class="flex space-x-2">
                    <select class="border rounded-md px-3 py-2">
                        <option>Trier par</option>
                        <option>Prix croissant</option>
                        <option>Prix décroissant</option>
                        <option>Nouveautés</option>
                        <option>Populaires</option>
                    </select>
                    <select id="category-filter" class="border rounded-md px-3 py-2">
  <option value="all">Toutes catégories</option>
  <option value="BTP/INDUSTRIE">BTP/INDUSTRIE</option>
  <option value="Restauration">Restauration</option>
  <option value="Santé">Santé</option>
</select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Section Produits -->
<section id="products" class="py-12">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-8">Tous les Produits</h2>

    <?php if (empty($produits)): ?>
      <p class="text-center text-gray-600">Aucun produit disponible pour le moment.</p>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($produits as $produit): ?>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
               data-price="<?= $produit['prix'] ?>"
               data-date="<?= $produit['date_ajout'] ?>"
               data-rating="<?= $produit['note'] ?>"
               data-category="<?= htmlspecialchars($produit['categorie']) ?>">
            <div class="relative">
              <img src="images/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="w-full h-64 object-cover">
              <?php if ($produit['promo'] > 0): ?>
                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-<?= $produit['promo'] ?>%</div>
              <?php elseif ($produit['avis'] <= 5): ?>
                <div class="absolute top-2 right-2 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($produit['nom']) ?></h3>
              <div class="flex items-center mb-2">
                <div class="flex text-yellow-400">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= floor($produit['note'])): ?>
                      <i class="fas fa-star"></i>
                    <?php elseif ($i == ceil($produit['note']) && fmod($produit['note'], 1) >= 0.5): ?>
                      <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                      <i class="far fa-star"></i>
                    <?php endif; ?>
                  <?php endfor; ?>
                </div>
                <span class="text-gray-600 text-sm ml-2">(<?= $produit['avis'] ?>)</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <?php if ($produit['promo'] > 0): ?>
                    <span class="text-gray-500 line-through text-sm"><?= number_format($produit['prix'], 2) ?> MAD</span>
                    <span class="text-indigo-600 font-bold ml-2">
                      <?= number_format($produit['prix'] * (1 - $produit['promo']/100), 2) ?> MAD
                    </span>
                  <?php else: ?>
                    <span class="text-indigo-600 font-bold"><?= number_format($produit['prix'], 2) ?> MAD</span>
                  <?php endif; ?>
                </div>
                <button class="view-product-btn"
                        data-id="<?= $produit['id'] ?>"
                        data-name="<?= htmlspecialchars($produit['nom']) ?>"
                        data-price="<?= $produit['prix'] ?>"
                        data-colors='[
                          <?php
                          $base = pathinfo($produit['image'], PATHINFO_FILENAME);
                          $colors = [
                            ['name' => 'Blanc',  'file' => "$base.jpg"],
                            ['name' => 'Noir',   'file' => "$base (1).jpg"],
                            ['name' => 'Bleu',   'file' => "$base (2).jpg"],
                            ['name' => 'Gris',   'file' => "$base (3).jpg"],
                            ['name' => 'Rouge',  'file' => "$base (4).jpg"],
                            ['name' => 'Jaune',  'file' => "$base (5).jpg"]
                          ];
                          $validColors = [];
                          foreach ($colors as $color) {
                            $filepath = "images/" . $color['file'];
                            if (file_exists($filepath)) {
                              $validColors[] = json_encode([
                                'name' => $color['name'],
                                'image' => $filepath
                              ]);
                            }
                          }
                          echo implode(",", $validColors);
                          ?>
                        ]'
                        data-description="<?= htmlspecialchars($produit['description']) ?>"
                        data-size-type="<?= $produit['type_taille'] ?? 'alpha' ?>">
                  <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

    <!-- Pagination -->
<div class="mt-12 text-center">
    <nav class="inline-flex items-center space-x-1">
        <!-- Flèche Précédent -->
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 bg-indigo-600 text-white rounded-l-md hover:bg-indigo-700">←</a>
        <?php else: ?>
            <span class="px-3 py-2 bg-gray-300 text-gray-600 rounded-l-md cursor-not-allowed">←</span>
        <?php endif; ?>

        <!-- Numéros de pages -->
        <?php for ($i = 1; $i <= $nombrePages; $i++): ?>
            <a href="?page=<?= $i ?>"
               class="px-3 py-2 <?= $i == $page ? 'bg-indigo-600 text-white' : 'bg-white text-indigo-600 hover:bg-indigo-100' ?> border border-gray-300 rounded-md">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Flèche Suivant -->
        <?php if ($page < $nombrePages): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">→</a>
        <?php else: ?>
            <span class="px-3 py-2 bg-gray-300 text-gray-600 rounded-r-md cursor-not-allowed">→</span>
        <?php endif; ?>
    </nav>

    <p class="mt-4 text-gray-600 text-sm">
        Page <?= $page ?> sur <?= $nombrePages ?> (<?= $totalProduits ?> produits au total)
    </p>
</div>
    

    <!-- Product Modal -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto mx-4">
    <div class="p-6">
      <!-- En-tête avec fermeture -->
      <div class="flex justify-between items-start mb-4">
        <h2 id="modal-product-name" class="text-2xl font-bold"></h2>
        <button id="close-modal" class="text-gray-500 hover:text-gray-700 text-3xl leading-none">&times;</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Image et couleurs -->
        <div>
          <img id="modal-product-image" src="" alt="Product" class="w-full rounded-lg">
          <div class="flex mt-4 space-x-2" id="color-options">
            <!-- Les couleurs seront ajoutées ici dynamiquement -->
          </div>
        </div>

        <!-- Détails du produit -->
        <div>
          <div class="mb-4">
            <span id="modal-product-price" class="text-2xl font-bold text-indigo-600"></span>
          </div>

          <p id="modal-product-description" class="text-gray-700 mb-6"></p>

          <!-- Tailles -->
          <div class="mb-6">
            <h3 class="font-semibold mb-2">Taille:</h3>
            <div class="flex flex-wrap gap-2" id="size-options">
              <!-- Tailles générées dynamiquement -->
            </div>
          </div>

          <!-- Quantité et panier -->
          <div class="flex items-center mb-6">
            <div class="flex items-center border rounded-md mr-4">
              <button id="decrease-qty" class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
              <span id="product-qty" class="px-4 py-2">1</span>
              <button id="increase-qty" class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
            </div>
            <button id="add-to-cart-btn" class="bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 flex-1">
              Ajouter au panier
            </button>
          </div>

          <!-- Informations de livraison -->
          <div class="border-t pt-4">
            <div class="flex items-center text-gray-600 mb-2">
              <i class="fas fa-truck mr-2"></i>
              <span>Livraison gratuite à partir de 500 MAD</span>
            </div>
            <div class="flex items-center text-gray-600">
              <i class="fas fa-undo mr-2"></i>
              <span>Retours gratuits sous 14 jours</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

       

    <!-- Features Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Livraison Rapide</h3>
                    <p class="text-gray-600">Livraison express dans tout le Maroc en 2-3 jours ouvrables.</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Paiement Sécurisé</h3>
                    <p class="text-gray-600">Transactions 100% sécurisées avec cryptage SSL.</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Support 24/7</h3>
                    <p class="text-gray-600">Notre équipe est disponible pour répondre à vos questions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Abonnez-vous à notre newsletter</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Recevez les dernières tendances, offres spéciales et réductions directement dans votre boîte de réception.</p>
            <div class="max-w-md mx-auto flex">
                <input type="email" placeholder="Votre email" class="flex-1 px-4 py-3 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-r-md font-semibold hover:bg-indigo-700">S'abonner</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Multi-Confection</h3>
                    <p class="text-gray-400">La meilleure destination pour la mode marocaine en ligne. Qualité, style et confort à des prix abordables.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="#products" class="text-gray-400 hover:text-white">Produits</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Nouveautés</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Promotions</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Informations</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">À propos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Livraison</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Politique de retour</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Conditions générales</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> 123 Rue de la Mode, Casablanca</li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2"></i> +212 6 12 34 56 78</li>
                        <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> contact@modemaroc.com</li>
                    </ul>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Multi-Confection. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
    // Fonction pour obtenir la couleur en hexadécimal
    function getColorHex(colorName) {
    const colors = {
        'Blanc': '#ffffff',
        'Noir': '#000000',
        'Bleu': '#3b82f6',
        'Bleu ciel': '#38bdf8',
        'Bleu gras': '#1e3a8a',
        'Rouge': '#ef4444',
        'Vert': '#10b981',
        'Jaune': '#f59e0b',
        'Orange': '#f97316',
        'Gris': '#6b7280',
        'Marron': '#78350f',
        'Violet': '#8a2be2',
        'Beige': '#f5f5dc',
        'Vert Gras': '#0a7856'
    };
    return colors[colorName] || '#cccccc'; // gris par défaut
}

    // Variables globales
    let currentProduct = null;
    let currentColor = null;
    let selectedSize = null;
    let cart = [];

    const productsPerPage = 12;
    let currentPage = 1;
    let allProducts = [];

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Cart dropdown toggle
    const cartBtn = document.getElementById('cart-btn');
    const cartDropdown = document.getElementById('cart-dropdown');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    if (cartBtn && cartDropdown) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('hidden');
        });

        // Close cart dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!cartDropdown.contains(e.target) && e.target !== cartBtn) {
                cartDropdown.classList.add('hidden');
            }
        });
    }

    // Product modal elements
    const productModal = document.getElementById('product-modal');
    const closeModal = document.getElementById('close-modal');
    const modalProductImage = document.getElementById('modal-product-image');
    const modalProductName = document.getElementById('modal-product-name');
    const modalProductPrice = document.getElementById('modal-product-price');
    const modalProductDescription = document.getElementById('modal-product-description');
    const colorOptionsContainer = document.getElementById('color-options');
    const decreaseQty = document.getElementById('decrease-qty');
    const increaseQty = document.getElementById('increase-qty');
    const productQty = document.getElementById('product-qty');
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    // Generate size buttons (S to XXL or 36 to 60)
    function generateSizeButtons(sizeType = 'alpha') {
        const sizeContainer = document.getElementById('size-options');
        sizeContainer.innerHTML = '';
        let sizes = [];

        if (sizeType === 'numeric') {
            // Pantalons : 36 à 60 (par 2)
            for (let i = 36; i <= 60; i += 2) {
                sizes.push(i.toString());
            }
        } else {
            // T-shirts : S, M, L, XL, XXL
            sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        }

        sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = size;
            btn.className = 'px-4 py-2 border rounded-md hover:bg-gray-100 size-option';
            btn.dataset.size = size;
            btn.addEventListener('click', () => {
                document.querySelectorAll('#size-options button').forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white');
                });
                btn.classList.add('bg-indigo-600', 'text-white');
                selectedSize = size;
            });
            sizeContainer.appendChild(btn);
        });

        if (sizes.length > 0) {
            selectedSize = sizes[0];
            sizeContainer.querySelector('button').classList.add('bg-indigo-600', 'text-white');
        }
    }

    // Load products per page
    function loadProductsPage(page) {
        const productContainer = document.querySelector('#products .grid');
        if (!productContainer) return;

        productContainer.innerHTML = '';

        const start = (page - 1) * productsPerPage;
        const end = start + productsPerPage;
        const productsToShow = allProducts.slice(start, end);

        productsToShow.forEach(product => {
            productContainer.appendChild(product.element);
        });

        updatePagination();
    }

    // Update pagination
    function updatePagination() {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;

        paginationContainer.innerHTML = '';

        const totalPages = Math.ceil(allProducts.length / productsPerPage);

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '←';
        prevBtn.className = 'px-3 py-1 rounded-md border hover:bg-gray-100';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadProductsPage(currentPage);
            }
        });
        paginationContainer.appendChild(prevBtn);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded-md border hover:bg-gray-100 ${i === currentPage ? 'bg-indigo-600 text-white' : ''}`;
            btn.addEventListener('click', () => {
                currentPage = i;
                loadProductsPage(currentPage);
            });
            paginationContainer.appendChild(btn);
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '→';
        nextBtn.className = 'px-3 py-1 rounded-md border hover:bg-gray-100';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadProductsPage(currentPage);
            }
        });
        paginationContainer.appendChild(nextBtn);
    }

    // Register all products
    document.querySelectorAll('.view-product-btn').forEach(btn => {
        const productElement = btn.closest('.bg-white');
        const productId = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-name');
        const productPrice = btn.getAttribute('data-price');
        const productDescription = btn.getAttribute('data-description');
        let colors;
try {
    const colorData = btn.getAttribute('data-colors');
    // Remplacer les ' par " et échapper les espaces dans les URLs
    const cleaned = colorData.replace(/'/g, '"');
    colors = JSON.parse(cleaned);
} catch (err) {
    console.error("Erreur JSON couleurs:", err);
    colors = [{ name: "Unique", image: modalProductImage.src }];
}
        const sizeType = btn.getAttribute('data-size-type') || 'alpha';

        allProducts.push({
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            description: productDescription,
            colors: colors,
            sizeType: sizeType,
            element: productElement.cloneNode(true)
        });

        // Hide product initially for pagination
        productElement.style.display = 'none';
    });

    // Load first page
    loadProductsPage(currentPage);

    // Open product modal
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.view-product-btn');
        if (!btn) return;

        const productId = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-name');
        const productPrice = btn.getAttribute('data-price');
        const productDescription = btn.getAttribute('data-description');
        const colors = JSON.parse(btn.getAttribute('data-colors'));
        const sizeType = btn.getAttribute('data-size-type') || 'alpha';

        currentProduct = {
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            description: productDescription,
            colors: colors
        };

        currentColor = colors[0];

        // Update modal content
        modalProductName.textContent = productName;
        modalProductPrice.textContent = productPrice + ' MAD';
        modalProductDescription.textContent = productDescription;
        modalProductImage.src = colors[0].image;
        productQty.textContent = '1';

        // Generate sizes
        generateSizeButtons(sizeType);

        // Create color options
        colorOptionsContainer.innerHTML = '';
        colors.forEach((color, index) => {
            const colorOption = document.createElement('div');
            colorOption.className = `w-10 h-10 rounded-full cursor-pointer color-option ${index === 0 ? 'selected' : ''}`;
            colorOption.style.backgroundColor = getColorHex(color.name);
            colorOption.setAttribute('data-color-index', index);
            colorOption.setAttribute('title', color.name);
            colorOption.addEventListener('click', () => {
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                colorOption.classList.add('selected');
                currentColor = colors[index];
                modalProductImage.src = color.image;
            });
            colorOptionsContainer.appendChild(colorOption);
        });

        // Show modal
        if (productModal) productModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close product modal
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            if (productModal) productModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
    }

    // Quantity controls
    if (decreaseQty && increaseQty && productQty) {
        decreaseQty.addEventListener('click', () => {
            let qty = parseInt(productQty.textContent);
            if (qty > 1) {
                productQty.textContent = qty - 1;
            }
        });

        increaseQty.addEventListener('click', () => {
            let qty = parseInt(productQty.textContent);
            productQty.textContent = qty + 1;
        });
    }

    // Add to cart
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            if (!currentProduct || !selectedSize) return;

            const qty = parseInt(productQty.textContent);
            const itemKey = `${currentProduct.id}-${currentColor.name}-${selectedSize}`;

            const existingItem = cart.find(item => item.key === itemKey);

            if (existingItem) {
                existingItem.quantity += qty;
            } else {
                cart.push({
                    key: itemKey,
                    id: currentProduct.id,
                    name: currentProduct.name,
                    price: currentProduct.price,
                    color: currentColor.name,
                    colorHex: getColorHex(currentColor.name),
                    image: currentColor.image,
                    size: selectedSize,
                    quantity: qty
                });
            }

            updateCartUI();
            if (productModal) productModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            alert('Produit ajouté au panier !');
        });
    }

    // Update cart UI
    function updateCartUI() {
        if (!cartItemsContainer || !cartCount || !cartTotal) return;
        cartItemsContainer.innerHTML = '';
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Votre panier est vide</p>';
            cartCount.textContent = '0';
            cartTotal.textContent = '0 MAD';
            return;
        }

        let total = 0;
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            const cartItem = document.createElement('div');
            cartItem.className = 'flex items-center py-2 border-b';
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                <div class="ml-4 flex-1">
                    <h4 class="font-medium">${item.name}</h4>
                    <p class="text-sm text-gray-600">Couleur: ${item.color}</p>
                    <p class="text-sm text-gray-600">Taille: ${item.size}</p>
                    <div class="flex justify-between mt-1">
                        <span class="text-gray-800">${item.price} MAD x ${item.quantity}</span>
                        <span class="font-semibold">${itemTotal.toFixed(2)} MAD</span>
                    </div>
                </div>
                <button class="remove-item ml-2 text-red-500 hover:text-red-700" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartTotal.textContent = total.toFixed(2) + ' MAD';

        // Remove item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(btn.getAttribute('data-index'));
                cart.splice(index, 1);
                updateCartUI();
            });
        });
    }

    // Function to sort products
    function sortProducts(criteria) {
        const productContainer = document.querySelector('#products .grid');
        const products = Array.from(productContainer.children);

        let sorted = [];

        if (criteria === 'price-asc') {
            sorted = [...products].sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.text-indigo-600')?.textContent);
                const priceB = parseFloat(b.querySelector('.text-indigo-600')?.textContent);
                return priceA - priceB;
            });
        } else if (criteria === 'price-desc') {
            sorted = [...products].sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.text-indigo-600')?.textContent);
                const priceB = parseFloat(b.querySelector('.text-indigo-600')?.textContent);
                return priceB - priceA;
            });
        } else if (criteria === 'newest') {
            sorted = [...products].sort((a, b) => {
                const dateA = a.getAttribute('data-date') || '1970-01-01';
                const dateB = b.getAttribute('data-date') || '1970-01-01';
                return new Date(dateB) - new Date(dateA);
            });
        } else if (criteria === 'popular') {
            sorted = [...products].sort((a, b) => {
                const ratingA = parseFloat(a.querySelector('.text-sm.ml-2')?.textContent.replace(/[^\d.]/g, '') || 0);
                const ratingB = parseFloat(b.querySelector('.text-sm.ml-2')?.textContent.replace(/[^\d.]/g, '') || 0);
                return ratingB - ratingA;
            });
        } else {
            sorted = products;
        }

        productContainer.innerHTML = '';
        sorted.forEach(p => productContainer.appendChild(p));
    }

    // Event listener for sort dropdown
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            sortProducts(e.target.value);
        });
    }

    // Update cart UI on load
    updateCartUI();
    // Gestion du formulaire de commande
const cartForm = document.getElementById('cart-form');
if (cartForm) {
    cartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('cart-data').value = JSON.stringify(cart);
        this.submit();
    });
}

// Fonction de filtrage par catégorie
function filterProducts() {
  const selectedCategory = document.getElementById('category-filter').value;
  const products = document.querySelectorAll('#products .grid > div[data-category]');

  products.forEach(product => {
    const productCategory = product.getAttribute('data-category');

    if (selectedCategory === 'all' || selectedCategory === productCategory) {
      product.style.display = 'block';
    } else {
      product.style.display = 'none';
    }
  });
}

// Écouteur sur le changement de catégorie
document.getElementById('category-filter').addEventListener('change', filterProducts);

// Appel initial pour afficher tous les produits au chargement
document.addEventListener('DOMContentLoaded', filterProducts);

</script>
    <!-- Charger le JS du produit -->
<script src="mocassins-s2-primo.js" defer></script>
</body>
</html>