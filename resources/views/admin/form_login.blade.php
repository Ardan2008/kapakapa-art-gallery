<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <title>Kapakapa Art Gallery | Private Access</title>
    <style>
        :root {
            var(--gold): #C9A74E;
            var(--dark-bg): #0a0a0a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0a0a0a;
            color: #d1d5db;
        }
        
        .input-field {
            background-color: #1a1a1a !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
            padding-left: 3.75rem !important; /* Ruang lebih untuk ikon + garis */
        }
        
        .input-field:focus {
            border-color: #C9A74E;
            outline: none;
            box-shadow: 0 0 0 1px #C9A74E;
        }

        /* Container untuk ikon dan garis */
        .icon-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3.25rem; /* Area tetap untuk ikon dan garis */
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        /* Ikon Lucide */
        .input-icon {
            color: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        /* Garis Vertikal Pemisah (Pseudo-element) */
        .icon-wrapper::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%; /* Jarak dari atas kotak */
            height: 50%; /* Panjang garis (setengah tinggi kotak) */
            width: 1px;
            background-color: rgba(255, 255, 255, 0.1); /* Warna garis default */
            transition: all 0.3s ease;
        }

        /* Efek Fokus: Ikon dan Garis berubah jadi emas */
        .input-group:focus-within .input-icon {
            color: #C9A74E;
        }
        
        .input-group:focus-within .icon-wrapper::after {
            background-color: #C9A74E;
            opacity: 0.5; /* Emas yang sedikit transparan agar tidak terlalu mencolok */
        }

        .btn-gold {
            background-color: #C9A74E;
            color: black;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: #e2c275;
            transform: translateY(-1px);
        }
        .btn-outline {
            border: 1px solid rgba(209, 213, 223, 0.2);
            color: #d1d5db;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: #d1d5db;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #1a1a1a inset !important;
            -webkit-text-fill-color: white !important;
        }

        /* forgot password */
        .modal-active {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        .modal-active #modalContent {
            transform: scale(1) !important;
        }

        /* Custom styling tambahan untuk input di dalam modal jika diperlukan */
        .input-field {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row overflow-x-hidden">

    <div class="w-full h-80 lg:h-screen lg:w-1/2 relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&q=80&w=2000" 
             alt="Gallery Art" 
             class="absolute inset-0 w-full h-full object-cover"
             data-aos="zoom-out" data-aos-duration="3000">
        
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent lg:bg-gradient-to-r lg:from-black/80 lg:via-black/20 to-transparent"></div>
        
        <div class="absolute bottom-10 left-10 lg:bottom-16 lg:left-16 z-10" data-aos="fade-up" data-aos-delay="800">
            <h1 class="text-4xl lg:text-5xl font-light text-gray-300 tracking-tighter">
                Kapakapa<span class="font-bold italic"> Art.</span>
            </h1>
            <div class="w-12 h-[2px] bg-[#C9A74E] mt-4 mb-4"></div>
            <p class="text-gray-300 text-[10px] lg:text-sm uppercase tracking-[0.3em]">Private Collection Portal</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-20 bg-[#0a0a0a]">
        <div class="w-full max-w-md">
            
            <div class="mb-12" data-aos="fade-down" data-aos-delay="1000">
                <h2 class="text-gray-300 text-3xl font-bold mb-2 tracking-tight">Gallery Administrator Access</h2>
                <p class="text-gray-400 text-sm">Sign in to access your dashboard and manage collections.</p>
            </div>

            <form id="authForm" method="POST" class="space-y-8">
                <div class="space-y-2" data-aos="fade-up" data-aos-delay="1200">
                    <label class="text-[10px] uppercase tracking-[0.2em] text-gray-300 font-bold ml-1">Username</label>
                    <div class="relative input-group">
                        <div class="icon-wrapper">
                            <i data-lucide="user" class="input-icon w-5 h-5"></i>
                        </div>
                        <input type="text" id="username" required
                            class="input-field w-full rounded-lg px-5 py-4 text-sm"
                            placeholder="Enter your username">
                    </div>
                </div>

                <div class="space-y-2" data-aos="fade-up" data-aos-delay="1300">
                    <label class="text-[10px] uppercase tracking-[0.2em] text-gray-300 font-bold ml-1">Password</label>
                    <div class="relative input-group">
                        <div class="icon-wrapper">
                            <i data-lucide="lock" class="input-icon w-5 h-5"></i>
                        </div>
                        <input type="password" id="password" required
                            class="input-field w-full rounded-lg px-5 py-4 text-sm tracking-[0.1em]"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex justify-end mt-4" data-aos="fade-up" data-aos-delay="1350">
                    <button type="button" 
                            onclick="toggleModal(true)" 
                            class="group relative text-[11px] font-medium uppercase tracking-[0.2em] text-gray-400 hover:text-[#C9A74E] transition-all duration-500 ease-in-out">
                        
                        <span>Forgot Password?</span>
                        
                        <span class="absolute -bottom-1 left-1/2 w-0 h-[1px] bg-[#C9A74E] transition-all duration-300 group-hover:w-full group-hover:left-0"></span>
                    </button>
                </div>

                <div class="flex flex-row gap-4 pt-4" data-aos="fade-up" data-aos-delay="1400">
                    <button type="button" onclick="handleCancel()"
                        class="btn-outline flex-1 font-bold py-5 rounded-lg uppercase text-xs tracking-[0.3em]">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="btn-gold flex-1 font-bold py-5 rounded-lg uppercase text-xs tracking-[0.3em] shadow-lg shadow-[#C9A74E]/10">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-20 flex flex-col items-center opacity-40" data-aos="fade-in" data-aos-delay="1600">
                <div class="flex items-center gap-4 w-full">
                    <div class="h-[1px] bg-gray-600 flex-1"></div>
                    <span class="text-[9px] uppercase tracking-[0.5em] text-gray-300 whitespace-nowrap">Kapakapa Art Gallery</span>
                    <div class="h-[1px] bg-gray-600 flex-1"></div>
                </div>
                <p class="mt-4 text-[8px] uppercase tracking-[0.2em] text-gray-400">Secure Environment • 2026</p>
            </div>
        </div>
    </div>

    <div id="resetModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-500 ease-in-out">
        <div class="w-full max-w-md bg-[#121212] border border-white/5 p-8 rounded-2xl shadow-2xl transform scale-95 transition-all duration-500 ease-in-out" id="modalContent">
            <div class="mb-8">
                <h2 class="text-gray-300 text-2xl font-bold mb-2 tracking-tight">Reset Password</h2>
                <p class="text-gray-400 text-xs">Please enter your new credentials below.</p>
            </div>

            <form class="space-y-6">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-[0.2em] text-gray-300 font-bold ml-1">New Password</label>
                        <div class="relative">
                            <input type="password" id="new-password" required 
                                class="reset-input w-full rounded-lg px-5 py-4 text-sm bg-[#1a1a1a] border border-white/10 text-white focus:border-[#C9A74E] outline-none transition-all" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase tracking-[0.2em] text-gray-300 font-bold ml-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" required 
                                class="reset-input w-full rounded-lg px-5 py-4 text-sm bg-[#1a1a1a] border border-white/10 text-white focus:border-[#C9A74E] outline-none transition-all" 
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex flex-row gap-4 pt-4">
                    <button type="button" onclick="toggleModal(false)" class="flex-1 text-gray-400 font-bold py-4 rounded-lg uppercase text-[10px] tracking-[0.2em] border border-white/10 hover:bg-white/5 transition-all">
                        Cancel
                    </button>
                    <button type="button" onclick="handleResetPassword()"
                        class="btn-gold flex-1 font-bold py-4 rounded-lg uppercase text-[10px] tracking-[0.2em] bg-[#C9A74E] text-black hover:bg-[#b08f3d] transition-all shadow-lg shadow-[#C9A74E]/20">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // logic untuk toggle modal
        function toggleModal(show) {
            const modal = document.getElementById('resetModal');
            
            if (show) {
                modal.classList.add('modal-active');
                // Mencegah scroll pada body saat modal terbuka
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.remove('modal-active');
                document.body.style.overflow = 'auto';
            }
        }

        // Konfigurasi Auto-Reveal Peek-a-boo
        const setupPeekPassword = (inputId) => {
            const input = document.getElementById(inputId);
            if (!input) return;

            let hideTimeout;

            // Saat user mengetik
            input.addEventListener('input', function() {
                this.type = 'text'; // Tampilkan teks
                
                // Bersihkan timeout sebelumnya jika user masih mengetik
                clearTimeout(hideTimeout);
                
                // Setel waktu 1 detik untuk menyembunyikan kembali
                hideTimeout = setTimeout(() => {
                    input.type = 'password';
                }, 1000);
            });

            // Saat user klik di luar input (blur), langsung sembunyikan
            input.addEventListener('blur', function() {
                clearTimeout(hideTimeout); // Batalkan timer jika ada
                this.type = 'password';
            });
        };

        // Inisialisasi untuk semua ID input yang ada
        document.addEventListener('DOMContentLoaded', () => {
            const targetIds = ['password', 'new-password', 'confirm-password'];
            targetIds.forEach(id => setupPeekPassword(id));
        });
        
        // Initialize Lucide Icons
        lucide.createIcons();

        // AOS Initialization
        AOS.init({
            duration: 1200,
            once: true,
            easing: 'ease-out-quart'
        });

        // Password Reveal Logic
        const passwordInput = document.getElementById('password');
        let hideTimeout;

        passwordInput.addEventListener('input', function() {
            this.type = 'text';
            clearTimeout(hideTimeout);
            hideTimeout = setTimeout(() => {
                passwordInput.type = 'password';
            }, 1000);
        });

        passwordInput.addEventListener('blur', function() {
            this.type = 'password';
        });

        async function handleResetPassword() {
            // Get username from the main login input (outside the modal)
            const usernameField = document.getElementById('username');
            const targetUser = usernameField ? usernameField.value : '';
            
            // Get new passwords from the modal inputs
            const newPassField = document.getElementById('new-password');
            const confirmPassField = document.getElementById('confirm-password');
            
            const newPass = newPassField.value;
            const confirmPass = confirmPassField.value;

            // --- INITIAL VALIDATION ---

            // Check if the username in the main login form is filled
            if (!targetUser) {
                toggleModal(false); // Close reset modal temporarily
                Swal.fire({
                    icon: 'info',
                    title: 'IDENTITY REQUIRED',
                    text: 'Please enter your username in the login form first.',
                    background: '#121212',
                    color: '#fff',
                    confirmButtonColor: '#C9A74E',
                    confirmButtonText: 'UNDERSTOOD'
                }).then(() => {
                    // Autofocus back to the username field
                    if (usernameField) usernameField.focus();
                });
                return;
            }

            // Check if password fields are empty
            if (!newPass || !confirmPass) {
                Swal.fire({
                    icon: 'error',
                    title: 'INCOMPLETE DATA',
                    text: 'Both password fields are required.',
                    background: '#121212',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            // Check if passwords match
            if (newPass !== confirmPass) {
                Swal.fire({
                    icon: 'warning',
                    title: 'MISMATCH',
                    text: 'Confirm password does not match the new password.',
                    background: '#121212',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            // --- CONFIRMATION & API PROCESS ---

            // Final confirmation from the user
            const result = await Swal.fire({
                title: 'RESET PASSWORD?',
                text: `You are about to update the password for administrator: ${targetUser}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C9A74E',
                cancelButtonColor: '#303030',
                confirmButtonText: 'YES, UPDATE NOW',
                cancelButtonText: 'CANCEL',
                background: '#121212',
                color: '#fff'
            });

            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'CONNECTING TO DATABASE...',
                    text: 'Please wait a moment.',
                    background: '#121212',
                    color: '#fff',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    // Replace this URL with your actual Laravel API endpoint
                    const response = await fetch('http://127.0.0.1:8000/api/forgot-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            username: targetUser,
                            password: newPass
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'SUCCESS!',
                            text: 'Password has been updated. Please login with your new credentials.',
                            background: '#121212',
                            color: '#fff',
                            iconColor: '#C9A74E',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            willClose: () => {
                                // Close modal and clear password inputs
                                toggleModal(false);
                                newPassField.value = '';
                                confirmPassField.value = '';
                            }
                        });
                    } else {
                        // Handle backend errors (e.g., username not found)
                        throw new Error(data.message || 'Failed to update password.');
                    }
                } catch (error) {
                    // Display error message
                    Swal.fire({
                        icon: 'error',
                        title: 'PROCESS FAILED',
                        text: error.message,
                        background: '#121212',
                        color: '#fff',
                        confirmButtonColor: '#C9A74E'
                    });
                }
            }
        }

        // Helper function untuk error cepat
        function showErrorAlert(msg) {
            Swal.fire({
                icon: 'error',
                title: 'Opps!',
                text: msg,
                background: '#121212',
                color: '#fff',
                showConfirmButton: false,
                timer: 1500
            });
        }

        function handleCancel() {
            Swal.fire({
                title: 'TERMINATE?',
                text: "Return to the main gallery?",
                icon: 'question',
                iconColor: '#C9A74E',
                showCancelButton: true,
                confirmButtonText: 'Yes, Exit',
                cancelButtonText: 'Stay Here',
                background: '#151515',
                color: '#fff',
                confirmButtonColor: '#C9A74E',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/'; 
                }
            });
        }

        document.getElementById('authForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // 1. Ambil data dari input
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // 2. Tampilkan Loading SweetAlert
            Swal.fire({
                title: 'VERIFYING',
                text: "Connecting to secure server...",
                icon: 'info',
                iconColor: '#C9A74E',
                background: '#151515',
                color: '#fff',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch('http://127.0.0.1:8000/api/login', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // JIKA BERHASIL
                    Swal.fire({
                        title: 'SUCCESS',
                        text: 'Welcome back, Administrator.',
                        icon: 'success',
                        iconColor: '#C9A74E',
                        background: '#151515',
                        color: '#fff',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/master'; // Pindah ke halaman admin
                    });
                } else {
                    // JIKA GAGAL (Contoh: Username salah)
                    throw new Error(data.message || 'Access Denied: Invalid Credentials');
                }

            } catch (error) {
                // Tampilkan pesan error dari Database/Laravel
                Swal.fire({
                    title: 'AUTH ERROR',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#C9A74E',
                    background: '#151515',
                    color: '#fff',
                });
            }
        });
    </script>
</body>
</html>