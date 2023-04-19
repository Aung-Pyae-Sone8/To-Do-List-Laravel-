@extends('master')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-6 offset-3">
                <div class="my-3">
                    <a href="{{ route('post#home') }}" class="text-decoration-none text-black">
                        <i class="fa-solid fa-arrow-left"></i> back
                    </a>
                </div>
                <form action="{{ route('post#update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label>Post Title</label>
                    <input type="hidden" name="postId" id="" value="{{ $item[0]['id'] }}">
                    <input type="text" name="postTitle" class="form-control @error('postTitle') is-invalid @enderror"
                        value="{{ old('postTitle', $item[0]['title']) }}" placeholder="Enter post title...">
                    @error('postTitle')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="">
                        @if ($item[0]['image'] == null)
                            <img src="{{ asset('imgNotFound.png') }}" class="img-thumbnail mt-4 shadow-sm" alt="">
                        @else
                            <img src="{{ asset('storage/' . $item[0]['image']) }}" class="img-thumbnail mt-4 shadow-sm"
                                alt="">
                        @endif
                    </div>
                    <label for="">Image</label>
                            <input type="file" name="postImage" class="form-control @error('postImage') is-invalid @enderror mb-3" id="">
                            @error('postImage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                    <label for="">Post Description</label>
                    <textarea name="postDescription" class="form-control my-3 @error('postDescription') is-invalid @enderror" id=""
                        cols="30" rows="10" placeholder="Enter post description...">{{ old('postDescription', $item[0]['description']) }}</textarea>
                    @error('postDescription')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror


                    <label for="">Price</label>
                    <input type="text" class="form-control" name="postFee" value="{{ old('postFee' , $item[0]['price']) }}" placeholder="Enter Fee...">

                    <label for="">Address</label>
                    <input type="text" class="form-control" name="postAddress" value="{{ old('postAddress',$item[0]['address']) }}" placeholder="Enter Address...">

                    <label for="">Rating</label>
                    <input type="text" class="form-control" name="postRating" value="{{ old('postRating',$item[0]['rating']) }}" placeholder="Enter Rating...">

                    <input type="submit" class="btn bg-dark text-white float-end my-3" value="Update">
                </form>
            </div>
        </div>

    </div>
@endsection
