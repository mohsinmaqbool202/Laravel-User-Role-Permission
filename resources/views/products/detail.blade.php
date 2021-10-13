@extends('layouts.frontLayout.front_design')
@section('style')
<style>
    /*body { min-height: 100vh;background-image: linear-gradient(to top, #d9afd9 0%, #97d9e1 100%); }*/
    #exzoom {
        width: 420px;
    }
    /*.container { margin: 150px auto; max-width: 960px; }*/
    /*.hidden { display: none; }*/
</style>
@endsection
@section('content')
<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 padding-right">
				<div class="product-details"><!--product-details-->
					<div class="col-sm-8">
						<div class="exzoom" id="exzoom">
						    <!-- Images -->
						    <div class="exzoom_img_box">
						      <ul class='exzoom_img_ul'>
						      	<li><img src="{{ asset( 'images/backend_images/products/'.$productDetail->image)}}"/></li>
						      	@foreach($product_imgs as $img)
						        <li><img src="{{ asset( 'images/backend_images/products/'.$img->image)}}"/></li>
						        @endforeach
						      </ul>
						    </div>
						    <div class="exzoom_nav"></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="product-information"><!--/product-information-->
							<img src="images/product-details/new.jpg" class="newarrival" alt="" />
							<p><b>Name: {{ $productDetail->name }}</b></p>
							<p><b>Code: {{ $productDetail->code }}</b></p>
							<p><b>Price: PKR-{{ $productDetail->price }}</b></p>
							<p><b>Color: {{ $productDetail->color }}</b></p>
							<p><b>Condition:</b> New</p>
						</div><!--/product-information-->
					</div>
				</div><!--/product-details-->
			</div>
		</div>
	</div>
</section>
@endsection