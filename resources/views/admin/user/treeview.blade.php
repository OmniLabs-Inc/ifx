@extends('admin.layouts.master')
@section('title',__('Referral List'))
@section('style')
    <link rel="stylesheet" href="{{asset('user/css/tree.css')}}">
@stop
@section('content')

    <div class="transaction-area left-bottom-line-bg common-pd-bottom-3" >
        <div class="container" style="width: 100%;overflow:scroll">


            <section class="treeMainContainer">
                <div class="treeContainer d_f">
                    <div class="_NewBranch d_f">
                        <div class="_treeRoot d_f">
                            <div class="_treeBranch hasChildren">
                                <div class="_treeRaw d_f">
                                    <div class="_treeLeaf d_f">
                                        <div class="t_Data d_f">
                                            <p class="shortName"> You are here </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="_NewBranch d_f">
                                    <div class="_treeRoot d_f">
                                        <div class="_treeBranch hasChildren">
                                            <div class="_treeRaw d_f">
                                                <div class="_treeLeaf d_f">
                                                    <div class="t_Data d_f">
                                                        <p class="shortName">{{$tree_user1}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="_NewBranch d_f">
                                                <div class="_treeRoot d_f">
                                                    <div class="_treeBranch @if($userStage > 3) hasChildren @endif">
                                                        <div class="_treeRaw d_f">
                                                            <div class="_treeLeaf d_f">
                                                                <div class="t_Data d_f">
                                                                    <p class="shortName">{{$tree_user3}}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($userStage > 3)
                                                        <!-- NEW BRANCH UNDER TREE USER 3 -->
                                                <div class="_NewBranch d_f">
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user7}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch ">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user8}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <!-- END NEW BRANCH UNDER USER 3 -->
                                                    </div>
                                                </div>



                                                <div class="_treeRoot d_f">
                                                    <div class="_treeBranch @if($userStage > 3) hasChildren @endif">
                                                        <div class="_treeRaw d_f">
                                                            <div class="_treeLeaf d_f">
                                                                <div class="t_Data d_f">
                                                                    <p class="shortName">{{$tree_user4}}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($userStage > 3)
                                                        <!-- NEW BRANCH UNDER TREE USER 4 -->
                                                <div class="_NewBranch d_f">
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user9}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch ">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user10}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <!-- END NEW BRANCH UNDER USER 4 -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_treeRoot d_f">
                                        <div class="_treeBranch hasChildren">
                                            <div class="_treeRaw d_f">
                                                <div class="_treeLeaf d_f">
                                                    <div class="t_Data d_f">
                                                        <p class="shortName"> {{$tree_user2}} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="_NewBranch d_f">
                                                <div class="_treeRoot d_f">
                                                    <div class="_treeBranch @if($userStage > 3) hasChildren @endif">
                                                        <div class="_treeRaw d_f">
                                                            <div class="_treeLeaf d_f">
                                                                <div class="t_Data d_f">
                                                                    <p class="shortName">{{$tree_user5}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($userStage > 3)
                                                        <!-- NEW BRANCH UNDER TREE USER 5 -->
                                                <div class="_NewBranch d_f">
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user11}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch ">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user12}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <!-- END NEW BRANCH UNDER USER 5 -->

                                                    </div>
                                                </div>
                                                <div class="_treeRoot d_f">
                                                    <div class="_treeBranch @if($userStage > 3) hasChildren @endif">
                                                        <div class="_treeRaw d_f">
                                                            <div class="_treeLeaf d_f">
                                                                <div class="t_Data d_f">
                                                                    <p class="shortName">{{$tree_user6}}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if($userStage > 3)
                                                        <!-- NEW BRANCH UNDER TREE USER 6 -->
                                                <div class="_NewBranch d_f">
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user13}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="_treeRoot d_f">
                                                        <div class="_treeBranch ">
                                                            <div class="_treeRaw d_f">
                                                                <div class="_treeLeaf d_f">
                                                                    <div class="t_Data d_f">
                                                                        <p class="shortName">{{$tree_user14}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <!-- END NEW BRANCH UNDER USER 6 -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </div>

@endsection

@push('styles')
<style>
    * {
	padding: 0;
	margin: 0;
	box-sizing: border-box;
}

:root {
	/* colors */
	--black: #3e3e3e;
	--white: #ffffff;
	--baseBg: #e6e6e6;
	--blue: #058aad;
	--lightBlue: #dbf3fa;


	/* Animation */
	--transition: all 0.3s ease-in;

	/* font name */
	--roboto: 'Roboto', sans-serif;
	--borderGap: 25px;
}

body {
	font-size: 22px;
	line-height: 36px;
	color: var(--black);
	font-family: var(--jameel);
	background-color: var(--baseBg);
}

.d_f {
	display: -webkit-flex;
	display: -moz-flex;
	display: -ms-flex;
	display: -o-flex;
	display: flex;
}

section {
	width: 100%;
	height: 100vh;
	position: relative;
	float: left;
	z-index: 0;
}

/* ----------------[ Shajra Tree ]---------------- */

	/* ==============  // Mapping child  ============== */
	.treeMainContainer {
		margin: 0 auto;
	    max-width: 100%;
	    width: 100vw;
	}
	.treeContainer {
		width: 100%;
		position: relative;
		padding: 50px;
		z-index: 0;
	}
	._treeRoot {
	    width: 100%;
	    position: relative;
	    flex-wrap: wrap;
	    justify-content: flex-start;
	    align-content: flex-start;
	    z-index: 0;
	}
	._treeBranch {
	    width: auto;
	    height: min-content;
	    position: relative;
	    min-height: 20px;
	    z-index: 0;
	}
	._treeRaw {
	    position: relative;
	    width: 100%;
	    z-index: 0;
	}
	._treeRaw.active, ._treeRoot.active {z-index: 10;}
	._treeLeaf {
	    position: relative;
	    width: 100%;
	    justify-content: center;
	    align-items: flex-start;
	    padding-bottom: var(--borderGap);
	    z-index: 1;
	    margin: 0 15px;
	}
	._treeData {
	    min-width: 180px;
	    height: 60px;
	    line-height: 60px;
	    text-align: center;
	    border: 1px solid #333;
	    font-size: 20px;
	    background-color: #fff;
	    padding: 0 50px;
	    white-space: nowrap;
	    position: relative;
	    text-align: center;
	    margin-right: 10px;
	    z-index: 0;
	}
	._treeData:last-child {margin-right: 0; }
	._NewBranch {
		position: relative;
		justify-content: center;
		align-content: flex-start;
		align-items: flex-start;
	}
	._NewBranch > div {flex: 1; }

	/* ==============  Table Cell Data  ============== */
		.t_Data {
			margin: 0 auto;
			position: relative;
			width: auto;
			max-width: 250px;
			min-width: 180px;
			height: 50px;
			background-color: var(--white);
			color: var(--green);
			align-items: center;
			padding: 0 10px;
			border-radius: 5px;
			transition: var(--transition);
			cursor: pointer;
			z-index: 1;
		}
		.t_Data p {
			flex: 1;
			font-size: 16px;
			text-align: center;
			white-space: nowrap;
		    text-overflow: ellipsis;
		    overflow: hidden;
		}
		.t_Data:hover, .t_Data.active {
			background-color: var(--blue);
			color: var(--white);
		}
		.t_Data:hover ._readMore, .t_Data.active ._readMore {background-image: url('../images/moreWhite.svg'); }
		.t_Data.active {background-color: var(--blue);z-index: 10;}
		.t_Data.active ._readMore {transform: rotate(0deg); }
		.t_Data.active .optnsCnt {display: block;}
		.t_Data.active .optnBx {margin-top: 0;}
		.optnsCnt {
			position: absolute;
			top: calc(100% + 5px);
			left: 0;
			width: 100%;
			/* display: none; */
			overflow: hidden;
		}
		.optnBx {
			position: relative;
			height: 65px;
			background-color: var(--white);
			border-radius: 5px;
			justify-content: center;
			align-items: center;
			align-content: center;
			padding: 8px 6px;
			margin-bottom: 5px;
			margin-top: -200px;
			transition: var(--transition);
		}
		.optnBx::after {
			content: '';
			width: 1px;
			height: 20px;
			background-color: #333;
			position: absolute;
			left: calc(50% - 0.5px);
			top: calc(50% - 10px);
		}
		.optnBx a {
			flex: 1;
		    position: relative;
		    height: 100%;
		    margin: 2px;
		    line-height: 55px;
		    border-radius: 5px;
		    font-size: 24px;
		    text-align: right;
		    padding: 0 15px 0 10px;
		    transition: var(--transition);
		}
		.optnBx a i {width: 30px;font-size: 22px;margin-left: 10px;}
		.optnBx a:hover {
			background-color: var(--blue);
			color: var(--white);
		}
	/* ==============  // Table Cell Data  ============== */

	/* ==============  Mapping Cell/children  ============== */
		.hasChild > ._treeRaw::after,
		.hasChildren > ._treeRaw::after {
			content: '';
			width: 1px;
			height: var(--borderGap);
			position: absolute;
			top: calc(100% - var(--borderGap));
			left: calc(50% - 0.5px);
			background-color: #555;
		}
		.hasChild > ._treeRaw:last-child::after {display: none;}
		.hasChildren > ._NewBranch {padding-top: var(--borderGap); }
		.hasChildren > ._NewBranch > ._treeRoot::before,
		.hasChildren > ._NewBranch > ._treeRoot::after {
			display: block;
		}
		._NewBranch > ._treeRoot::before,
		._NewBranch > ._treeRoot::after {
			display: none;
			content: '';
		    position: absolute;
		    right: 50%;
		    width: 50%;
		    height: var(--borderGap);
		    top: calc(0% - var(--borderGap));
		    border-top: 1px solid #333;
		}
		._NewBranch > ._treeRoot::before {right: 0;}
		._NewBranch > ._treeRoot::after {left: 0;}

		._NewBranch > ._treeRoot:first-child::after,
		._NewBranch > ._treeRoot:last-child::before {display: none;}

		.hasChildren > ._NewBranch > ._treeRoot ._treeLeaf::after {
		    content: '';
		    width: 1px;
		    position: absolute;
		    height: var(--borderGap);
		    background-color: #333;
		    bottom: 100%;
		}
	/* ==============  // Mapping Cell  ============== */
/* ----------------[ Shajra Tree ]---------------- */

</style>

@endpush
