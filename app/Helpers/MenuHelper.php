<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Dapatkan item navigasi utama berdasarkan peran pengguna.
     */
    public static function getMainNavItems()
    {
        $user = auth()->user();
        $items = [];

        // adm
        if ($user && $user->isAdmin()) {
            $items[] = [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/dashboard',
            ];
            $items[] = [
                'icon' => 'user-profile',
                'name' => 'Kelola Pengguna',
                'path' => '/pengguna',
            ];
            $items[] = [
                'icon' => 'forms',
                'name' => 'Kelola Kerja Sama',
                'path' => '/kerja-sama',
            ];

            // data alumni sub-menu
            $items[] = [
                'icon' => 'alumni-bekerja',
                'name' => 'Data Alumni',
                'path' => '#',
                'subItems' => [
                    [
                        'icon' => 'alumni-bekerja',
                        'name' => 'Alumni Bekerja',
                        'path' => '/alumni-bekerja',
                    ],
                    [
                        'icon' => 'alumni-kuliah',
                        'name' => 'Alumni Kuliah',
                        'path' => '/bk/alumni-kuliah',
                    ],
                ],
            ];

            $items[] = [
                'icon' => 'perusahaan-mitra',
                'name' => 'Data Perusahaan Mitra',
                'path' => '/perusahaan-mitra',
            ];
            $items[] = [
                'icon' => 'universitas',
                'name' => 'Universitas',
                'path' => '/universitas',
            ];
            $items[] = [
                'icon' => 'lowongan-kerja',
                'name' => 'Lowongan Kerja',
                'path' => '/lowongan-kerja',
            ];

            // tracer sub-menu
            $items[] = [
                'icon' => 'tracer-study',
                'name' => 'Tracer Study',
                'path' => '#',
                'subItems' => [
                    [
                        'icon' => 'tracer-study',
                        'name' => 'Tracer Bekerja',
                        'path' => '/tracer-study',
                    ],
                    [
                        'icon' => 'tracer-study',
                        'name' => 'Tracer Kuliah',
                        'path' => '/bk/tracer-kuliah',
                    ],
                ],
            ];

            // laporn sub-menu
            $items[] = [
                'icon' => 'laporan',
                'name' => 'Laporan',
                'path' => '#',
                'subItems' => [
                    [
                        'icon' => 'laporan',
                        'name' => 'Laporan BKK',
                        'path' => '/laporan-bkk',
                    ],
                    [
                        'icon' => 'laporan',
                        'name' => 'Laporan BK',
                        'path' => '/bk/laporan',
                    ],
                    [
                        'icon' => 'laporan',
                        'name' => 'Laporan Kerja Sama',
                        'path' => '/laporan-kerja-sama',
                    ],
                ],
            ];
        }

        // ════ BKK ONLY ════
        elseif ($user && $user->isBKK()) {
            $items[] = [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/dashboard',
            ];
            $items[] = [
                'icon' => 'forms',
                'name' => 'Kelola Kerja Sama',
                'path' => '/kerja-sama',
            ];
            $items[] = [
                'icon' => 'laporan',
                'name' => 'Laporan Kerja Sama',
                'path' => '/laporan-kerja-sama',
            ];
            $items[] = [
                'icon' => 'alumni-bekerja',
                'name' => 'Data Alumni Bekerja',
                'path' => '/alumni-bekerja',
            ];
            $items[] = [
                'icon' => 'perusahaan-mitra',
                'name' => 'Data Perusahaan Mitra',
                'path' => '/perusahaan-mitra',
            ];
            $items[] = [
                'icon' => 'lowongan-kerja',
                'name' => 'Lowongan Kerja',
                'path' => '/lowongan-kerja',
            ];
            $items[] = [
                'icon' => 'tracer-study',
                'name' => 'Tracer Study',
                'path' => '/tracer-study',
            ];
            $items[] = [
                'icon' => 'laporan',
                'name' => 'Laporan BKK',
                'path' => '/laporan-bkk',
            ];
        }

        // bk
        elseif ($user && $user->isBK()) {
            $items[] = [
                'icon' => 'dashboard',
                'name' => 'Dashboard BK',
                'path' => '/dashboard',
            ];
            $items[] = [
                'icon' => 'alumni-kuliah',
                'name' => 'Alumni Kuliah',
                'path' => '/bk/alumni-kuliah',
            ];
            $items[] = [
                'icon' => 'universitas',
                'name' => 'Universitas',
                'path' => '/bk/universitas',
            ];
            $items[] = [
                'icon' => 'tracer-study',
                'name' => 'Tracer Study Kuliah',
                'path' => '/bk/tracer-kuliah',
            ];
            $items[] = [
                'icon' => 'laporan',
                'name' => 'Laporan',
                'path' => '/bk/laporan',
            ];
        }

        // ════ NOTIFIKASI (ADMIN & BKK) ════
        if ($user && ($user->isAdmin() || $user->isBKK())) {
            $items[] = [
                'icon' => 'bell',
                'name' => 'Notifikasi',
                'path' => '/notifikasi',
            ];
        }

        // ════ PENGATURAN (ADMIN ONLY) ════
        if ($user && $user->isAdmin()) {
            $items[] = [
                'icon' => 'settings',
                'name' => 'Pengaturan',
                'path' => '/pengaturan',
            ];
        }

        return $items;
    }

    /**
     * Dapatkan pengelompokan menu untuk sidebar.
     */
    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu Utama',
                'items' => self::getMainNavItems()
            ]
        ];
    }

    /**
     * Periksa apakah menu sedang aktif berdasarkan path saat ini.
     */
    public static function isActive($path)
    {
        $currentPath = request()->path();
        $targetPath = ltrim($path, '/');

        if ($targetPath === '') {
            return $currentPath === '/' || $currentPath === 'dashboard';
        }

        return request()->is($targetPath) || request()->is($targetPath . '/*');
    }

    /**
     * Dapatkan kode SVG untuk ikon menu.
     */
    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
            'user-profile' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor"></path></svg>',
            'forms' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5001C19.7427 20.75 20.7501 19.7426 20.7501 18.5V5.5C20.7501 4.25736 19.7427 3.25 18.5001 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5001C18.9143 4.75 19.2501 5.08579 19.2501 5.5V18.5C19.2501 18.9142 18.9143 19.25 18.5001 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM6.25005 9.7143C6.25005 9.30008 6.58583 8.9643 7.00005 8.9643L17 8.96429C17.4143 8.96429 17.75 9.30008 17.75 9.71429C17.75 10.1285 17.4143 10.4643 17 10.4643L7.00005 10.4643C6.58583 10.4643 6.25005 10.1285 6.25005 9.7143ZM6.25005 14.2857C6.25005 13.8715 6.58583 13.5357 7.00005 13.5357H17C17.4143 13.5357 17.75 13.8715 17.75 14.2857C17.75 14.6999 17.4143 15.0357 17 15.0357H7.00005C6.58583 15.0357 6.25005 14.6999 6.25005 14.2857Z" fill="currentColor"></path></svg>',
            'bell' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C8.63 5.36 7 7.92 7 11v5l-2 2v1h14v-1l-2-2z" fill="currentColor"/></svg>',
            'settings' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84a.483.483 0 0 0-.48.4l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87a.49.49 0 0 0 .12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32a.49.49 0 0 0-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" fill="currentColor"/></svg>',
            'alumni-bekerja' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'perusahaan-mitra' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="10" width="20" height="11" rx="2" ry="2"/><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/></svg>',
            'lowongan-kerja' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
            'tracer-study' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>',
            'laporan' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
            'alumni-kuliah' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>',
            'universitas' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="10" width="20" height="11" rx="2"/><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}
