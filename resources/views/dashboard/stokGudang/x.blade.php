@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid p-4">
            <button type="button" class="btn btn-info btn-sm tomboltambahbanyak">
                <i class="fa fa-plus-circle"></i> tambah data banyak    
            </button>        
            <div>
                <p class="card-text viewdata"></p>
            </div>
            <div class="viewmodal" style="display: none"></div>
        </div>
    </main>
</div>
<script>
    $(document).ready(function(){
        $('.tomboltambahbanyak').click(function(e){
            e.preventDefault();
            $.ajax({
                url: "<?= site_url('dashboard/formtambahbanyak')?>",
                dataType: "json",
                beforeSend: function(){
                    $('.viewdata').html('<i class="fa fa-spin fa-spinner"></i>');
                }
                success: function(response){
                    $('.viewdata').html(response.data).show();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
@endsection
