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
			@if(Session::has('flash_message_error'))  
		        <div class="alert alert-error alert-block" style="background-color: #f2dfd0;">
		            <button type="button" class="close" data-dismiss="alert">x</button>
		            <strong>{{ session::get('flash_message_error') }}</strong>
		        </div>
		    @endif 
			<div class="col-sm-12 padding-right">
				<div class="product-details"><!--product-details-->
					<div class="col-sm-6">
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
					<div class="col-sm-6">
						<form name="addtocartForm" id="addtocartForm" action="{{ route('add.cart') }}" method="post">
							{{ csrf_field() }}
							<input type="hidden" name="product_id"    value="{{ $productDetail->id }}">
							<input type="hidden" name="user_email"    value="{{Session::get('customerSession')}}">
							<div class="product-information"><!--/product-information-->
								<img src="images/product-details/new.jpg" class="newarrival" alt="" />
								<h2>{{ $productDetail->name }}</h2>
								<p><b>Code:</b> {{ $productDetail->code }}</p>
								<p><b>Color:</b> {{ $productDetail->color }}</p>
								<p><b>Price:</b> PKR {{ $productDetail->price }}</p>
								<p><b>Available Stock:</b>
								  @if($productDetail->stock) 
								  	    {{$productDetail->stock}} Items 
								  	@else
								  		Out Of Stock
								  	@endif
								</p>
								<p><b>Condition:</b> New</p>
								<span>
									<label>Quantity:</label>
									<input type="text" name="quantity" value="1" />
									@if($productDetail->stock)
									<button type="submit" class="btn btn-fefault cart" id="cartButton">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
									@endif
								</span>
							</div><!--/product-information-->
						</form>  
					</div>
				</div><!--/product-details-->
			</div>
		</div>
	</div>
</section>
@endsection