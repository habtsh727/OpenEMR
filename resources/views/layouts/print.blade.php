{{-- resources/views/layouts/print.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Referral - {{ $referral->id ?? 'Document' }}</title>
    
    @vite(['resources/css/app.css'])
    
    <style>
        /* Print-specific styles */
        @media print {
            /* Hide non-printable elements */
            .no-print {
                display: none !important;
            }
            
            /* Show printable elements */
            .print-only {
                display: block !important;
            }
            
            /* Force background colors */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            /* Page breaks */
            .page-break-before {
                page-break-before: always;
            }
            
            .page-break-after {
                page-break-after: always;
            }
            
            .avoid-break {
                page-break-inside: avoid;
            }
            
            /* Print margins */
            @page {
                margin: 0.5in;
                size: letter;
            }
            
            body {
                margin: 0;
                padding: 0;
                background: white !important;
                color: black !important;
                font-size: 12pt;
            }
            
            /* Reset all dark mode styles for print */
            .dark\:bg-gray-900,
            .dark\:bg-gray-800,
            .dark\:bg-gray-700,
            .dark\:text-white,
            .dark\:text-gray-300,
            .dark\:border-gray-700 {
                background: white !important;
                color: black !important;
                border-color: #e5e7eb !important;
            }
            
            /* Ensure proper contrast */
            .text-gray-900,
            .text-gray-800,
            .text-gray-700 {
                color: black !important;
            }
            
            .text-gray-600,
            .text-gray-500,
            .text-gray-400 {
                color: #4b5563 !important;
            }
            
            /* Make sure badges are visible */
            .bg-blue-100 { background-color: #dbeafe !important; }
            .bg-yellow-100 { background-color: #fef3c7 !important; }
            .bg-red-100 { background-color: #fee2e2 !important; }
            .bg-green-100 { background-color: #d1fae5 !important; }
            .bg-gray-100 { background-color: #f3f4f6 !important; }
            
            /* Text colors for badges */
            .text-blue-800 { color: #1e40af !important; }
            .text-yellow-800 { color: #92400e !important; }
            .text-red-800 { color: #991b1b !important; }
            .text-green-800 { color: #065f46 !important; }
            .text-gray-800 { color: #1f2937 !important; }
            
            /* Borders for print */
            .border,
            .border-t,
            .border-b,
            .border-l,
            .border-r {
                border-color: #d1d5db !important;
            }
            
            /* Shadows for print */
            .shadow-lg,
            .shadow-md,
            .shadow-sm {
                box-shadow: none !important;
            }
            
            /* Special print elements */
            .print-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 1.5in;
                background: white !important;
                z-index: 1000;
            }
            
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 0.5in;
                background: white !important;
                z-index: 1000;
                border-top: 1px solid #d1d5db !important;
            }
            
            .print-content {
                margin-top: 1.5in;
                margin-bottom: 0.5in;
            }
            
            /* Watermarks and stamps */
            .print-watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 72px;
                color: rgba(0, 0, 0, 0.1);
                z-index: -1;
                white-space: nowrap;
                opacity: 0.1;
            }
            
            .print-stamp {
                position: absolute;
                top: 100px;
                right: 50px;
                transform: rotate(15deg);
                border: 3px solid currentColor;
                padding: 15px 25px;
                border-radius: 50%;
                font-weight: bold;
                font-size: 20px;
                background: rgba(255, 255, 255, 0.9);
                z-index: 10;
            }
            
            .urgent-stamp {
                border-color: #f59e0b !important;
                color: #f59e0b !important;
            }
            
            .emergency-stamp {
                border-color: #dc2626 !important;
                color: #dc2626 !important;
            }
        }
        
        /* Screen-only styles */
        @media screen {
            .print-only {
                display: none;
            }
            
            .print-stamp,
            .print-watermark {
                display: none;
            }
        }
        
        /* Common styles for both screen and print */
        .referral-border {
            border: 2px solid #1e40af;
            position: relative;
        }
        
        .referral-border::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1e40af, #3b82f6);
        }
        
        .referral-border::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1e40af, #3b82f6);
        }
    </style>
</head>
<body class="h-full bg-white dark:bg-gray-900">
    <!-- Screen Content (Hidden during print) -->
    <div class="no-print">
        {{ $slot }}
    </div>
    
    <!-- Print Content (Only shown during print) -->
    <div id="print-content" class="hidden print-only">
        <!-- Content will be inserted here for print -->
    </div>

    @livewireScripts
    
    <script>
        document.addEventListener('livewire:load', function() {
            // Handle print dialog
            Livewire.on('open-print-dialog', function() {
                // Wait for Livewire to finish updating
                setTimeout(() => {
                    generatePrintContent();
                    setTimeout(() => {
                        window.print();
                    }, 100);
                }, 100);
            });
            
            // Generate print content
            function generatePrintContent() {
                const printContainer = document.getElementById('print-content');
                const printSource = document.querySelector('[data-print-source]');
                
                if (printSource) {
                    // Clone the content
                    const content = printSource.cloneNode(true);
                    
                    // Remove no-print classes
                    content.querySelectorAll('.no-print').forEach(el => el.remove());
                    
                    // Add print-only classes
                    content.querySelectorAll('.print-only').forEach(el => {
                        el.classList.remove('hidden');
                    });
                    
                    // Clear and append
                    printContainer.innerHTML = '';
                    printContainer.appendChild(content);
                    printContainer.classList.remove('hidden');
                }
            }
            
            // Handle before/after print events
            window.addEventListener('beforeprint', () => {
                document.body.classList.add('printing');
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            });
            
            window.addEventListener('afterprint', () => {
                document.body.classList.remove('printing');
                document.getElementById('print-content').classList.add('hidden');
                document.getElementById('print-content').innerHTML = '';
                
                // Restore theme
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            });
            
            // Keyboard shortcut for print
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    Livewire.dispatch('open-print-dialog');
                }
            });
        });
    </script>
</body>
</html>