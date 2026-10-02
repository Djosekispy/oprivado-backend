<?php $date = Carbon\Carbon::yesterday()->format('Y-m-d'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
   <loc>{{ url('/') }}</loc>
   <priority>0.8</priority>
</url>

<url>
  <loc>{{ url('creators') }}</loc>
  <priority>0.6</priority>
</url>

<url>
   <loc>{{ url('contact') }}</loc>
   <priority>0.7</priority>
 </url>

 <url>
    <loc>{{ url('blog') }}</loc>
    <priority>0.5</priority>
  </url>

  @foreach (Blogs::all() as $post)
  <url>
     <loc>{{ url('blog/post', $post->id).'/'.$post->slug }}</loc>
     <priority>0.5</priority>
  </url>
   @endforeach

@foreach (Pages::all() as $page)
<url>
   <loc>{{ url('p', $page->slug) }}</loc>
   <priority>0.7</priority>
</url>
 @endforeach

 @foreach (Categories::where('mode', 'on')->get() as $category)
<url>
  <loc>{{ url('category', $category->slug) }}</loc>
  <priority>0.6</priority>
</url>
@endforeach

	@foreach (User::where('status','active')->where('verified_id', 'yes')->get() as $user)
<url>
   <loc>{{ url($user->username) }}</loc>
   <priority>0.6</priority>
</url>
   @endforeach

   <url>
      <loc>{{ url('shop') }}</loc>
      <priority>0.4</priority>
    </url>

    @foreach (Products::where('status', '1')->get() as $products)
   <url>
     <loc>{{ url('shop/product', $products->id) }}</loc>
     <priority>0.4</priority>
   </url>
   @endforeach
</urlset>
