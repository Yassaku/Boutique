<?php
session_start();

// Variables pour les messages
$success = false;
$error = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (!empty($nom) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($sujet) && !empty($message)) {
        
        // Destinataire (change par ton email)
        $to = "contact@multiconfection.ma";
        $subject = "Nouveau message - $sujet";
        $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        // Envoi de l'email
        if (mail($to, $subject, $body, $headers)) {
            $success = true;
            // Réinitialiser les champs après envoi
            $_POST['nom'] = $_POST['email'] = $_POST['sujet'] = $_POST['message'] = '';
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Contact - Multi-Confection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        #map {
            height: 300px;
            width: 100%;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }
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
            <a href="BTPINDUSTRIE.php" class="text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
            <a href="Restauration.php" class="text-gray-700 hover:text-indigo-600">Restauration</a>
            <a href="Sante.php" class="text-gray-700 hover:text-indigo-600">Santé</a>
            <a href="contact.php" class="font-semibold text-indigo-600">Contact</a>
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
<section class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Nous Contacter</h1>
        <p class="text-xl mb-6">Une question ? Un besoin ? Remplissez le formulaire ou visitez notre atelier.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Informations de contact -->
            <div>
                <h2 class="text-3xl font-bold mb-6">Informations</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-indigo-600 text-xl mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Adresse</h3>
                            <p class="text-gray-600">Zone Industrielle Sidi Maârouf, Casablanca, Maroc</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-phone-alt text-indigo-600 text-xl mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Téléphone</h3>
                            <p class="text-gray-600">+212 6 00 00 00 00</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-envelope text-indigo-600 text-xl mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Email</h3>
                            <p class="text-gray-600">contact@multiconfection.ma</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-clock text-indigo-600 text-xl mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Horaires</h3>
                            <p class="text-gray-600">Lun - Sam : 8h30 - 18h00</p>
                        </div>
                    </div>
                </div>
                

                <!-- Carte Google Maps -->
                <div id="map" class="mt-6"></div>
                <script>
                    function initMap() {
                        const location = { lat: 33.5738, lng: -7.6434 }; // Casablanca
                        const map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 13,
                            center: location,
                        });
                        new google.maps.Marker({
                            position: location,
                            map: map,
                            title: "Multi-Confection - Casablanca"
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
                <!-- 🔽 Remplace `YOUR_API_KEY` par ta clé Google Maps (ou supprime la carte si tu ne veux pas l'utiliser) -->
            </div>

            <!-- Formulaire de contact -->
            <div>
                <h2 class="text-3xl font-bold mb-6">Envoyez-nous un message</h2>
                <!-- Formulaire de contact -->
<div>
    

    <!-- Messages d'alerte -->
    <?php if (isset($success) && $success): ?>
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
            ✅ Merci ! Votre message a été envoyé avec succès. Nous vous répondrons sous 24-48h.
        </div>
    <?php elseif (isset($error) && $error): ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-md mb-6">
            ❌ Erreur : Veuillez remplir tous les champs correctement.
        </div>
    <?php endif; ?>

    <form method="POST" action="contact.php" class="space-y-6">
        <!-- Nom complet -->
        <div>
            <label for="nom" class="block text-gray-700 font-medium mb-1">Nom complet *</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">Email *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Sujet -->
        <div>
            <label for="sujet" class="block text-gray-700 font-medium mb-1">Sujet *</label>
            <input type="text" id="sujet" name="sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>"
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Message -->
        <div>
            <label for="message" class="block text-gray-700 font-medium mb-1">Message *</label>
            <textarea id="message" name="message" rows="5" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>

        <!-- Bouton Envoyer -->
        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-md transition duration-200 transform hover:scale-105">
            Envoyer le message
        </button>
    </form>
</div>

                <?php if ($success): ?>
                    <p class="bg-green-100 text-green-800 p-4 rounded-md mb-6">Merci ! Votre message a été envoyé avec succès.</p>
                <?php elseif ($error): ?>
                    <p class="bg-red-100 text-red-800 p-4 rounded-md mb-6">Erreur : Veuillez remplir tous les champs correctement.</p>
                <?php endif; ?>

                <form method="POST" action="contact.php" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1" for="