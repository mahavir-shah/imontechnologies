@extends('layouts.admin')
@section('page-title')
    {{__('POS Barcode Print')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('pos.barcode')}}">{{__('POS Product Barcode')}}</a></li>
    <li class="breadcrumb-item">{{__('POS Barcode Print')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
        }
        .barcodeBreak{
            width: 100px;
            height: 50px;
            padding-left: 20px
        }
        .barcode_detail p{
            font-size: 4px;
        }
        .barcode_detail p:first-child{
            padding-right: 10px;
        }
        .tech-head{
            font-size: 4px;
        }
        @page {size: 595px 842px; margin:0!important; padding:0!important}
    </style>
@endpush

@push('script-page')

    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');

            var opt = {
                margin: 0,
                padding:0,
                filename: filename,
                image: { type: 'jpeg', quality:1},
                html2canvas: { 
                    dpi: 271,
                    letterRendering: true,
                    useCORS: true,
                    windowWidth:200,
                    windowHeight:200,
                    scale : 4
                },
                jsPDF: { unit: 'in', format: [0.9,0.6], orientation: 'landscape' },        
                pagebreak: { mode: 'avoid-all', after: '.barcodeBreak' }
            };
            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                window.open(pdf.output('bloburl'), '_blank');
            });

        }
    </script>
@endpush


@section('action-btn')
    <div class="float-end">
        <a href="{{ route('pos.barcode') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Back')}}">
            <i class="ti ti-arrow-left text-white"></i>
        </a>
        <button class="btn btn-sm btn-primary" onclick="saveAsPDF()">
            {{__('Print')}}
        </a>
    </div>
@endsection


@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="printableArea">
                        @if (count($purchase) > 0) 
                            @foreach ($purchase as $item)
                                <div class="barcodeBreak">
                                    <div class="barcode mt-2">
                                        <div class="barcode_detail">
                                            <p class="tech-head m-0"><b>Imon </b>Technologies</p>
                                        </div>
                                        {!! DNS1D::getBarcodeHTML($item->barcode, "C128", 0.4, 20) !!} 
                                        {{-- 
                                            0.6, 37
                                        C128 = barcode type
                                        1.4 = barcode weight
                                        22 = barcode height
                                        --}}
                                        <div class="barcode_detail d-flex">
                                            <p class="pid">{{$item->barcode}}</p>
                                            <p class="m-0">{{$item->sr_no}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                        <div class="barcode">
                            <p class="pt-2 px-3">No Barcode Found.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


