<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Staff Portal</title>
    <!-- Tailwind CSS -->
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    <!-- Optional: Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-stone-50 text-stone-800 antialiased flex h-screen overflow-hidden">

    <!-- Sidebar Component -->
    <?= $this->include('components/staff_sidebar') ?>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Header Component -->
        <?= $this->include('components/staff_header') ?>


        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-stone-50 p-8">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Floating Chatbot Widget -->
    <?= $this->include('components/chatbot') ?>


    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 z-10 hidden md:hidden"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const backdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('absolute');
            sidebar.classList.toggle('inset-y-0');
            sidebar.classList.toggle('left-0');
            backdrop.classList.toggle('hidden');
        }

        if(mobileMenuBtn && backdrop) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            backdrop.addEventListener('click', toggleSidebar);
        }
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
