<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Binary Tree</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        /*Now the CSS*/
        * {
            margin: 0;
            padding: 0;
        }

        .tree ul {
            padding-top: 20px;
            position: relative;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        .tree li {
            float: left;
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        /*We will use ::before and ::after to draw the connectors*/

        .tree li::before,
        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 1px solid #ccc;
            width: 50%;
            height: 20px;
        }

        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 1px solid #ccc;
        }

        /*We need to remove left-right connectors from elements without
any siblings*/
        .tree li:only-child::after,
        .tree li:only-child::before {
            display: none;
        }

        /*Remove space from the top of single children*/
        .tree li:only-child {
            padding-top: 0;
        }

        /*Remove left connector from first child and
right connector from last child*/
        .tree li:first-child::before,
        .tree li:last-child::after {
            border: 0 none;
        }

        /*Adding back the vertical connector to the last nodes*/
        .tree li:last-child::before {
            border-right: 1px solid #ccc;
            border-radius: 0 5px 0 0;
            -webkit-border-radius: 0 5px 0 0;
            -moz-border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
            -webkit-border-radius: 5px 0 0 0;
            -moz-border-radius: 5px 0 0 0;
        }

        /*Time to add downward connectors from parents*/
        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid #ccc;
            width: 0;
            height: 20px;
        }

        .tree li a {
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-decoration: none;
            color: #666;
            font-family: arial, verdana, tahoma;
            font-size: 11px;
            display: inline-block;

            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        /*Time for some hover effects*/
        /*We will apply the hover effect the the lineage of the element also*/
        .tree li a:hover,
        .tree li a:hover+ul li a {
            background: #c8e4f8;
            color: #000;
            border: 1px solid #94a0b4;
        }

        /*Connector styles on hover*/
        .tree li a:hover+ul li::after,
        .tree li a:hover+ul li::before,
        .tree li a:hover+ul::before,
        .tree li a:hover+ul ul::before {
            border-color: #94a0b4;
        }

        /*Thats all. I hope you enjoyed it.
Thanks :)*/
    </style>
</head>

<body>
    {{-- {{ $root }} --}}
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tree">
                    <ul>

                        <li>
                            <a href="#">{{ $root->user_id }}</a>
                            <ul>
                                @foreach ($root->children as $level1)
                                    {{-- LEVEL 1 - --}}
                                    <li>
                                        <a href="#">{{ $level1->user_id }}</a>
                                        <ul>
                                            @foreach ($level1->children as $level2)
                                                {{-- LEVEL 2 - --}}
                                                <li>
                                                    <a href="#">{{ $level2->user_id }}</a>
                                                    <ul>
                                                        @foreach ($level2->children as $level3)
                                                            {{-- LEVEL 3 - --}}
                                                            <li>
                                                                <a href="#">{{ $level3->user_id }}</a>
                                                                <ul>
                                                                    @foreach ($level3->children as $level4)
                                                                        {{-- LEVEL 4 - --}}
                                                                        <li>
                                                                            <a href="#">{{ $level4->user_id }}</a>
                                                                            <ul>
                                                                                @foreach ($level4->children as $level5)
                                                                                    {{-- LEVEL 5 - --}}
                                                                                    <li>
                                                                                        <a
                                                                                            href="#">{{ $level5->user_id }}</a>
                                                                                        <ul>
                                                                                            @foreach ($level5->children as $level6)
                                                                                                {{-- LEVEL 6 - --}}
                                                                                                <li>
                                                                                                    <a
                                                                                                        href="#">{{ $level6->user_id }}</a>
                                                                                                    <ul>
                                                                                                        @foreach ($level6->children as $level7)
                                                                                                            {{-- LEVEL 7 - --}}
                                                                                                            <li>
                                                                                                                <a
                                                                                                                    href="#">{{ $level7->user_id }}</a>
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

</body>

</html>
