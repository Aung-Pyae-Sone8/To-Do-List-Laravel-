@extends('master')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-5">
                <div class="p-3">

                    @if (session('insertSuccess'))
                        <div class="alert-message">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('insertSuccess') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    @if (session('updateSuccess'))
                        <div class="alert-message">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{ session('updateSuccess') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    <form action="{{ route('post#create') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="text-group mb-3">
                            <label for="">Post Title</label>
                            <input type="text" class="form-control @error('postTitle') is-invalid @enderror" name="postTitle" value="{{ old('postTitle') }}" placeholder="Enter Post Title...">
                            @error('postTitle')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="text-group mb-3">
                            <label for="">Post Description</label>
                            <textarea name="postDescription" id="" cols="30" rows="10"
                                class="form-control @error('postDescription') is-invalid @enderror" placeholder="Enter Post Description...">{{ old('postDescription') }}</textarea>
                            @error('postDescription')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-group mb-3">
                            <label for="">Image</label>
                            <input type="file" name="postImage" class="form-control @error('postImage') is-invalid @enderror" id="" value="{{old('postImage')}}">
                            @error('postImage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-group mb-3">
                            <label for="">Fee</label>
                            <input type="number" name="postFee" class="form-control @error('postFee') is-invalid @enderror" id="" value="{{old('postFee')}}" placeholder="Enter post fee...">
                            @error('postFee')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-group mb-3">
                            <label for="">Address</label>
                            <input type="text" name="postAddress" class="form-control @error('postAddress') is-invalid @enderror" id="" value="{{old('postAddress')}}" placeholder="Enter your address...">
                            @error('postAddress')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="text-group mb-3">
                            <label for="">Rating</label>
                            <input type="number" name="postRating" min="0" max="5" class="form-control @error('postRating') is-invalid @enderror" id="" value="{{old('postRating')}}">
                            @error('postRating')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class=" mb-3">
                            <input type="submit" value="Create" class="btn btn-primary bg-gradient">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-7">
                <div class="mb-3">
                    <h3>
                        <div class="row">
                            <div class="col-5">Total - {{ $posts->total() }} Posts</div>
                            <div class="col-5 offset-2">
                                <form action="{{ route('post#createPage') }}" method="get">
                                    <div class="row">
                                        <input type="text" name="searchKey" placeholder="Enter search title..."
                                            value="{{ request('searchKey') }}" class="form-control col" id="">
                                        <button class="btn btn-secondary col-2" type="submit">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
                <div class="data-container">
                    {{-- @for ($i = 0; i < count($posts); $i++)  --}}
                    @if (count($posts) != 0)
                        @foreach ($posts as $item)
                            <div class="post p-3 shadow-sm mb-4">
                                <div class="row">
                                    <h5 class="col-7">{{ $item['title'] }}</h5>
                                    <h5 class="col-4 offset-1">{{ $item->created_at->format('d-m-Y n:iA') }}</h5>
                                    {{-- <span class="col-4">{{$item['created_at']->format('d m Y')}}</span> --}}
                                </div>
                                {{-- <p class="text-muted">{{substr($item['description'],0,100)}}</p> --}}
                                <p class="text-muted">{{ Str::words($item['description'], 30, '......') }}</p>
                                <span>
                                    <small><i class="fa-solid fa-money-bill-1 text-success"></i> {{ $item->price }}
                                        Kyats</small>
                                </span> |
                                <span>
                                    <i class="fa-solid fa-location-dot text-danger"></i> {{ $item->address }}
                                </span> |
                                <span>
                                    {{ $item->rating }} <i class="fa-solid fa-star text-warning"></i>
                                </span>
                                <div class="text-end">
                                    <a href="{{ route('post#delete', $item['id']) }}">
                                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </a>
                                    <a href="{{ route('post#updatePage', $item['id']) }}">
                                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-file-invoice"></i></button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                            <h3 class="text-muted text-center mt-5">There is no data...</h3>
                    @endif

                </div>
                {{ $posts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
