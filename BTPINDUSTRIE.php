<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'boutique'; // Remplace par ton nom de base
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer uniquement les produits de la catégorie "BTP/INDUSTRIE"
$stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = 'BTP/INDUSTRIE' ORDER BY date_ajout DESC");
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>BTP / INDUSTRIE - Multi-Confection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .animate-slideIn {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #product-modal { max-height: 90vh; }
        #modal-product-image { max-height: 300px; }
        #product-modal .grid-cols-1.md\:grid-cols-2 { align-items: center; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Header -->
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <a href="index.php" class="text-2xl font-bold text-indigo-600">
                <img src="images/Nouveau projet.png" alt="Multi Confection" class="logo h-10 w-auto">
            </a>
            <a href="index.php" class="text-gray-700 hover:text-indigo-600">Accueil</a>
            <a href="index.php#products" class="text-gray-700 hover:text-indigo-600">Produits</a>
            <a href="BTPINDUSTRIE.php" class="font-semibold text-indigo-600">BTP/INDUSTRIE</a>
            <a href="Restauration.php" class="text-gray-700 hover:text-indigo-600">Restauration</a>
            <a href="Sante.php" class="text-gray-700 hover:text-indigo-600">Santé</a>
            <a href="contact.php" class="text-gray-700 hover:text-indigo-600">Contact</a>
        </div>
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="text-indigo-600 hover:text-indigo-800">Mon Compte</a>
            <?php else: ?>
                <a href="connexion.php" class="text-gray-700 hover:text-indigo-600">Compte</a>
                <a href="inscription.php" class="text-gray-700 hover:text-indigo-600">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">BTP & INDUSTRIE</h1>
        <p class="text-xl mb-6">Équipement professionnel haute performance pour les métiers du bâtiment, de l'industrie et de la sécurité.</p>
        <a href="#products" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 inline-block">Voir les produits</a>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Produits BTP / INDUSTRIE</h2>

        <?php if (empty($produits)): ?>
            <p class="text-center text-gray-600">Aucun produit disponible dans cette catégorie pour le moment.</p>
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
                                            $colors = [
                                                ["name" => "Blanc", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . ".jpg"],
                                                ["name" => "Noir", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . " (1).jpg"],
                                                ["name" => "Bleu", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . " (2).jpg"],
                                                ["name" => "Gris", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . " (3).jpg"],
                                                ["name" => "Rouge", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . " (4).jpg"],
                                                ["name" => "Jaune", "image" => "images/" . pathinfo($produit['image'], PATHINFO_FILENAME) . " (5).jpg"]
                                            ];
                                            $validColors = [];
                                            foreach ($colors as $color) {
                                                if (file_exists($color['image'])) {
                                                    $validColors[] = json_encode($color);
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

<!-- Product Modal -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h2 id="modal-product-name" class="text-2xl font-bold"></h2>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <img id="modal-product-image" src="" alt="" class="w-full rounded-lg">
                <div>
                    <div class="mb-4">
                        <span id="modal-product-price" class="text-2xl font-bold text-indigo-600"></span>
                    </div>
                    <p id="modal-product-description" class="text-gray-700 mb-6"></p>
                    <h3 class="font-semibold mb-2">Couleur:</h3>
                    <div class="flex space-x-2 mb-4" id="color-options"></div>
                    <h3 class="font-semibold mb-2">Taille:</h3>
                    <div class="flex flex-wrap gap-2 mb-6" id="size-options"></div>
                    <button class="w-full bg-indigo-600 text-white py-3 rounded-md font-semibold hover:bg-indigo-700">Ajouter au panier</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold mb-4">À propos</h3>
                <p class="text-gray-400">Spécialiste de la vente de vêtements professionnels pour BTP, restauration et santé. Qualité, durabilité et confort garantis.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-4">Liens rapides</h3>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-400 hover:text-white">Accueil</a></li>
                    <li><a href="index.php#products" class="text-gray-400 hover:text-white">Produits</a></li>
                    <li><a href="BTPINDUSTRIE.php" class="text-gray-400 hover:text-white">BTP/INDUSTRIE</a></li>
                    <li><a href="Restauration.php" class="text-gray-400 hover:text-white">Restauration</a></li>
                    <li><a href="Sante.php" class="text-gray-400 hover:text-white">Santé</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Livraison</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Retours</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Conditions</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            &copy; <?= date('Y') ?> Multi-Confection. Tous droits réservés.
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('product-modal');
        const closeModal = document.getElementById('close-modal');
        const modalName = document.getElementById('modal-product-name');
        const modalPrice = document.getElementById('modal-product-price');
        const modalImage = document.getElementById('modal-product-image');
        const modalDescription = document.getElementById('modal-product-description');
        const colorOptions = document.getElementById('color-options');
        const sizeOptions = document.getElementById('size-options');

        let currentProduct = null;

        // Ouvrir la modale
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
                colors: colors,
                sizeType: sizeType
            };

            modalName.textContent = productName;
            modalPrice.textContent = `${parseFloat(productPrice).toFixed(2)} MAD`;
            modalDescription.textContent = productDescription;
            modalImage.src = colors[0].image;

            // Générer les options de couleur
            colorOptions.innerHTML = '';
            colors.forEach(color => {
                const button = document.createElement('button');
                button.classList.add('w-8', 'h-8', 'rounded-full', 'border', 'border-gray-300', 'focus:outline-none');
                button.style.backgroundColor = color.color || '#ccc';
                button.title = color.name;
                button.onclick = () => { modalImage.src = color.image; };
                colorOptions.appendChild(button);
            });

            // Générer les options de taille
            sizeOptions.innerHTML = '';
            const sizes = sizeType === 'alpha' ? ['S', 'M', 'L', 'XL', 'XXL'] : ['36', '38', '40', '42', '44', '46', '48', '50', '52', '54', '56', '58', '60'];
            sizes.forEach(size => {
                const button = document.createElement('button');
                button.classList.add('px-3', 'py-1', 'border', 'border-gray-300', 'rounded-md', 'text-sm', 'hover:bg-indigo-100');
                button.textContent = size;
                button.onclick = () => {
                    sizeOptions.querySelectorAll('button').forEach(b => b.classList.remove('bg-indigo-100', 'font-bold'));
                    button.classList.add('bg-indigo-100', 'font-bold');
                };
                sizeOptions.appendChild(button);
            });

            modal.classList.remove('hidden');
        });

        // Fermer la modale
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>