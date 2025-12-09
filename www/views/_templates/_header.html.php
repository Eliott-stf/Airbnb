<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aribnb</title>
    <link rel="icon" type="image/png" href="/assets/img/favicon-96x96.png" sizes="96x96" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php
    // Afficher les messages flash (success et error) depuis la session
    // Les messages sont affichés en haut à droite avec auto-hide après 5 secondes
    use JulienLinard\Core\Session\Session;
    $headerSuccess = Session::getFlash('success');
    $headerError = Session::getFlash('error');
    ?>
    
    <?php if ($headerSuccess): ?>
    <div class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($headerSuccess) ?></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Auto-hide après 5 secondes
        setTimeout(() => {
            document.querySelector('.bg-green-50')?.parentElement?.remove();
        }, 5000);
    </script>
    <?php endif; ?>
    
    <?php if ($headerError): ?>
    <div class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800"><?= htmlspecialchars($headerError) ?></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Auto-hide après 5 secondes
        setTimeout(() => {
            document.querySelector('.bg-red-50')?.parentElement?.remove();
        }, 5000);
    </script>
    <?php endif; ?>

    
    <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
    
<?php if (!empty($headerHidden)) return; ?>
<header class="bg-[#fbfbfb] p-4 flex justify-between items-center shadow-xl">

    <div class="w-24 h-34">
        <a href="/">
            <img src="/assets/img/Airbnb_large.png" alt="Logo">
        </a>
    </div>

        <div class="hidden md:flex items-center bg-gray-100 rounded-full shadow-sm px-4 py-2 space-x-4">
            <input type="text" placeholder="Où allez-vous ?" class="bg-transparent outline-none text-gray-700 text-sm">
            <input type="date" class="bg-transparent outline-none text-gray-700 text-sm">
            <button class="px-4 py-2 bg-[#ff5a5f] text-white rounded-full hover:bg-red-800 transition">
                Rechercher
            </button>
        </div>

    

    <?php if (empty($posts)): ?>
        <div class="flex items-center space-x-4">
            <div>
                <span class="relative inline-block group">
                    <a href="/hote/create" class="relative z-10 px-4 py-2 text-sm font-medium text-gray-800">Devenir Hôte</a>
                    <span class="absolute inset-0 bg-gray-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </span>
            </div>
    <?php else: ?>
        <div class="flex items-center space-x-4">
            <div>
                <span class="relative inline-block group">
                    <a href="/hote/index" class="relative z-10 px-4 py-2 text-sm font-medium text-gray-800">Mes Biens</a>
                    <span class="absolute inset-0 bg-gray-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </span>
            </div>
    <?php endif; ?>

    <?php use JulienLinard\Core\View\ViewHelper ?>

    <?php if ($user): ?>
        <a href="/booking/show" class="text-sm text-gray-600 hover:text-gray-900 transition-colors mr-4">Mes réservations</a>
        <form action="/logout" method="POST" onsubmit="return confirm('Etes-vous sûr de vouloir vous déconnecter ?')" class="inline">
            <input type="hidden" name="_token" value="<?= htmlspecialchars(ViewHelper::csrfToken()) ?>">
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Déconnexion</button>
        </form>
    <?php else: ?>
        <a href="/login" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Connexion</a>
    <?php endif; ?>

    <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50 rounded-full flex items-center justify-center">
        <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 21a9 9 0 1 0 0-18m0 18a9 9 0 1 1 0-18m0 18c2.761 0 3.941-5.163 3.941-9S14.761 3 12 3m0 18c-2.761 0-3.941-5.163-3.941-9S9.239 3 12 3M3.5 9h17m-17 6h17m-17-4h17" />
            </svg>
        </a>
    </div>

    <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50 rounded-full flex items-center justify-center">
        <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="m2.75 12.25h10.5m-10.5-4h10.5m-10.5-4h10.5" />
            </svg>
        </a>
    </div>

</header>
