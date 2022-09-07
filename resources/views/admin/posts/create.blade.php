<h1 class="text-center-3x1 uppercase font my-4">Crie seu novo post</h1>

<div class="text-center text-3x1 uppercase font-black my-4">
    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
        @include('admin.posts._partials.form')
    </form>
</div>