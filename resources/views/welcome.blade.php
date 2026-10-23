


@if (Auth::user())
            <div class="h-14.5 hidden lg:block">
                <form action="{{route('logout')}}" method="post">
   @csrf
   <button type="submit">Logout</button>
</form>  
            </div>
        @endif
   
@if(Auth::check())
    <h2>Welcome {{ Auth::user()->name }}</h2>
@else
    <h2>You are a guest</h2>
@endif

<a href="{{route('products.index')}}">View Products</a>
<hr>
<a href="{{route('login')}}">Login</a>
 </body>
</html>