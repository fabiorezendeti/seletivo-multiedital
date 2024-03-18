@push('css')
<style>
    @media print {
        html {
            background: none !important;
        }

        @page {
            size: portrait !important;
        }

        

        body {
            width: 21cm !important;
            min-height: 29.7cm !important;
            margin: 1.5cm !important;
        }

        .justify-between {
            justify-content: normal !important;
        }

        body * {
            visibility: hidden;

        }
        

        #print-area {
            width: 100%;
            height: 100%;
            margin: 0px !important;
            padding: 0px !important;
        }

        #print-area * {
            visibility: visible;
        }


        #barra-brasil,
        #ally,
        #ally * {
            padding: 0 !important;
            margin: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }

    }
</style>
@endpush