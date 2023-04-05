<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   @foreach ($articles as $article)
      <url>
         <loc>{{ $base_url }}/{{$article->url}}</loc>
         <lastmod>{{$article->updated_at->format('Y-m-d')}}</lastmod>
         <changefreq>{{$changefreq['articles']}}</changefreq>
         <priority>{{$priorities['articles']}}</priority>
      </url>
   @endforeach

   @foreach ($categories as $category)
      <url>
         <loc>{{$base_url}}/{{$category->url}}</loc>
         <lastmod>{{$category->updated_at->format('Y-m-d')}}</lastmod>
         <changefreq>{{$changefreq['article_categories']}}</changefreq>
         <priority>{{$priorities['article_categories']}}</priority>
      </url>
   @endforeach

   @foreach ($tags as $tag)
      <url>
            <loc>{{$base_url}}/{{$tag->url}}</loc>
            <lastmod>{{$tag->updated_at->format('Y-m-d')}}</lastmod>
            <changefreq>{{$changefreq['article_tags']}}</changefreq>
            <priority>{{$priorities['article_tags']}}</priority>
      </url>
   @endforeach

   @foreach ($product_categories as $product_category)
      <url>
         <loc>{{$base_url}}/{{$product_category->url}}</loc>
         <lastmod>{{$product_category->updated_at->format('Y-m-d')}}</lastmod>
         <changefreq>{{$changefreq['product_categories']}}</changefreq>
         <priority>{{$priorities['product_categories']}}</priority>
      </url>
   @endforeach
   @foreach ($products as $product)
      <url>
            <loc>{{$base_url}}/{{$product['url']}}</loc>
            <lastmod>{{$product['updated_at']->format('Y-m-d')}}</lastmod>
            <changefreq>{{$changefreq['products']}}</changefreq>
            <priority>{{$priorities['products']}}</priority>
      </url>
   @endforeach

   @if ($static_pages !== null)
      @foreach ($static_pages as $static_page)
         <url>
               <loc>{{$base_url}}/{{$static_page['url']}}</loc>
               <lastmod>{{$static_page['updated_at']->format('Y-m-d')}}</lastmod>
               <changefreq>{{$changefreq['static_pages']}}</changefreq>
               <priority>{{$priorities['static_pages']}}</priority>
         </url>
      @endforeach
   @endif

   <url>
      <loc>{{$base_url}}/prijava</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>yearly</changefreq>
      <priority>0.8</priority>
   </url>

   <url>
      <loc>{{$base_url}}/registracija</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>yearly</changefreq>
      <priority>0.8</priority>
   </url>

   <url>
      <loc>{{ route('forgot_password') }}</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>yearly</changefreq>
      <priority>0.8</priority>
   </url>

   <url>
      <loc>{{$base_url}}/korpa</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>daily</changefreq>
      <priority>0.8</priority>
   </url>

   <url>
      <loc>{{$base_url}}/lista-zelja</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>yearly</changefreq>
      <priority>0.8</priority>
   </url>

   <url>
      <loc>{{$base_url}}/uporedi-proizvode</loc>
      <lastmod>2018-11-30</lastmod>
      <changefreq>yearly</changefreq>
      <priority>0.8</priority>
   </url>
</urlset>
