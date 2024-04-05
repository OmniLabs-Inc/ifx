@if (count($children) == 1)

<ul>
    <li>
        @if ($children[0]->side == "left")
        <a href="{{ url('binary/'.$children[0]->user_id) }}">
           {{ $children[0]->user_id}}
        </a>
        @else
        <a href="#">
            Empty
        </a>
        @endif
        @if ($children[0]->side =="left" && count($children[0]->children) > 0)
        @include("components.binarychild", ["children", $children[0]->children])
        @endif
    </li>
    <li>
        @if ($children[0]->side == "right")
        <a href="{{ url('binary/'.$children[0]->user_id) }}">
            {{$children[0]->user_id}}
        </a>
        @else
        <a href="#">
            Empty
        </a>
        @endif
        @if ($children[0]->side =="right" && count($children[0]->children) > 0)
        @include("components.binarychild", ["children", $children[0]->children])
        @endif
    </li>
</ul>

@endif

@if (count($children) == 2)
    <ul>
        <li>
            @if ($children[0]->side == 'left')
                <a href="{{ url('binary/' . $children[0]->user_id) }}">{{ $children[0]->user_id }}</a>
                @if (count($children[0]->children) > 0)
                    @include('components.binarychild', ['children' => $children[0]->children])
                @endif
            @endif
            @if ($children[1]->side == 'left')
                <a href="{{ url('binary/' . $children[1]->user_id) }}">{{ $children[1]->user_id }}</a>
                @if (count($children[1]->children) > 0)
                    @include('components.binarychild', ['children' => $children[1]->children])
                @endif
            @endif
        </li>
        <li>
            @if ($children[0]->side == 'right')
                <a href="{{ url('binary/' . $children[0]->user_id) }}">{{ $children[0]->user_id }}</a>
                @if (count($children[0]->children) > 0)
                    @include('components.binarychild', ['children' => $children[0]->children])
                @endif
            @endif
            @if ($children[1]->side == 'right')
                <a href="{{ url('binary/' . $children[1]->user_id) }}">{{ $children[1]->user_id }}</a>
                @if (count($children[1]->children) > 0)
                    @include('components.binarychild', ['children' => $children[1]->children])
                @endif
            @endif
        </li>
    </ul>
@endif
