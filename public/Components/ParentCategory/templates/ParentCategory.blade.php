@foreach($data as  $key => $section)
    <div class = "parent_category__section">
        <h4>{{$key}}</h4>
        @foreach($section as $name => $category)
        <div class = "parent_category__category_single">
            {{$category['name']}}
            <img src = "{{$category['img']}}" alt = "{{$category['name']}}" />
        </div>
        @endforeach
    </div>
@endforeach