<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drone Operation - Profile</title>
    <link rel="icon" href="{{ asset('admin_assets/img/cropped-asianagri_logo-2.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Back Button -->
            <div class="text-right mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Cards Container (Grid layout) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Update Profile Information -->
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900 mb-4">Update Your Profile</h1>
                            </div>
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="form-group">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" class="form-control form-control-user" type="text" name="name" :value="old('name', auth()->user()->name)" required autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email Address -->
                                <div class="form-group">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="form-control form-control-user" type="email" name="email" :value="old('email', auth()->user()->email)" required autocomplete="email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Save Button -->
                                <x-primary-button class="btn btn-primary btn-user btn-block">
                                    {{ __('Save Changes') }}
                                </x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900 mb-4">Update Your Password</h1>
                            </div>
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="form-group">
                                    <x-input-label for="current_password" :value="__('Current Password')" />
                                    <x-text-input id="current_password" class="form-control form-control-user" type="password" name="current_password" required autocomplete="current-password" />
                                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                </div>

                                <!-- New Password -->
                                <div class="form-group">
                                    <x-input-label for="new_password" :value="__('New Password')" />
                                    <x-text-input id="new_password" class="form-control form-control-user" type="password" name="new_password" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                                </div>

                                <!-- Confirm New Password -->
                                <div class="form-group">
                                    <x-input-label for="new_password_confirmation" :value="__('Confirm New Password')" />
                                    <x-text-input id="new_password_confirmation" class="form-control form-control-user" type="password" name="new_password_confirmation" required autocomplete="new-password_confirmation" />
                                    <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-2" />
                                </div>

                                <!-- Save Button -->
                                <x-primary-button class="btn btn-primary btn-user btn-block">
                                    {{ __('Update Password') }}
                                </x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete User -->
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900 mb-4">Delete Your Account</h1>
                            </div>
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <!-- Delete Button -->
                                <x-danger-button class="btn btn-danger btn-user btn-block">
                                    {{ __('Delete Account') }}
                                </x-danger-button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js')}}"></script>

</body>

</html>
