@extends('instructor.instructor_dashboard')
@section('instructor')

<div class="page-content">
    <div class="chat-wrapper">
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <div class="d-flex align-items-center">
                    <div class="chat-user-online">
                        <img src="{{ !empty(Auth::user()->photo) ? url('upload/instructor_images/' . Auth::user()->photo) : url('upload/no_image.jpg') }}" width="45" height="45" class="rounded-circle" alt="" />
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0">{{Auth::user()->name}}</p>
                    </div>
                   
                </div>
                <div class="mb-3"></div>
                <div class="input-group input-group-sm"> <span class="input-group-text bg-transparent"><i class='bx bx-search'></i></span>
                    <input type="text" class="form-control" placeholder="People, groups, & messages"> <span class="input-group-text bg-transparent"><i class='bx bx-dialpad'></i></span>
                </div>
                <div class="chat-tab-menu mt-3">
                  
                </div>
            </div>
            <div class="chat-sidebar-content">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-Chats">
                        
                        <div class="chat-list">
                            <div class="list-group list-group-flush">
                                <a href="javascript:;" class="list-group-item">
                                    <div class="d-flex">
                                        <div class="chat-user-online">
                                            <img src="{{ !empty($question->user->photo) ? url('upload/user_images/' . $question->user->photo) : url('upload/no_image.jpg') }}" width="42" height="42" class="rounded-circle" alt="" />
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">{{$question->user->name}}</h6>
                                            <p class="mb-0 chat-msg">{{$question->subject}}</p>
                                        </div>
                                        <div class="chat-time">{{$question->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                                
                               
                              
                              
                               
                            
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-header d-flex align-items-center">
            <div class="chat-toggle-btn"><i class='bx bx-menu-alt-left'></i>
            </div>
            <div>
                <h6 class="mb-1 font-weight-bold">{{$question->course->course_name}}</h6>
            </div>
            <div class="chat-top-header-menu ms-auto"> <a href="javascript:;"><i class='bx bx-video'></i></a>
                <a href="javascript:;"><i class='bx bx-phone'></i></a>
                <a href="javascript:;"><i class='bx bx-user-plus'></i></a>
            </div>
        </div>
        <div class="chat-content">
            <div class="chat-content-leftside">
                <div class="d-flex">
                    <img src="{{ !empty($question->user->photo) ? url('upload/user_images/' . $question->user->photo) : url('upload/no_image.jpg') }}" width="48" height="48" class="rounded-circle" alt="" />
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0 chat-time" title="{{ $question->created_at->format('l, F j, Y \a\t g:i A') }}">
                            {{ $question->subject }}, 
                            @if ($question->created_at->isToday())
                                {{ $question->created_at->format('g:i A') }}
                            @elseif ($question->created_at->isYesterday())
                                Yesterday at {{ $question->created_at->format('g:i A') }}
                            @elseif ($question->created_at->format('Y') == now()->format('Y'))
                                {{ $question->created_at->format('M j, g:i A') }}
                            @else
                                {{ $question->created_at->format('M j, Y, g:i A') }}
                            @endif
                        </p>
                            <p class="chat-left-msg">{{$question->question}}</p>
                    </div>
                </div>
            </div>
            @foreach ($replies as $replay)
            <div class="chat-content-rightside">
                <div class="d-flex ms-auto">
                    <div class="flex-grow-1 me-2">
                        <p class="mb-0 chat-time text-end" title="{{ $replay->created_at->format('l, F j, Y \a\t g:i A') }}">
                           You, 
                            @if ($replay->created_at->isToday())
                                {{ $replay->created_at->format('g:i A') }}
                            @elseif ($replay->created_at->isYesterday())
                                Yesterday at {{ $replay->created_at->format('g:i A') }}
                            @elseif ($replay->created_at->format('Y') == now()->format('Y'))
                                {{ $replay->created_at->format('M j, g:i A') }}
                            @else
                                {{ $replay->created_at->format('M j, Y, g:i A') }}
                            @endif
                        </p>                        
                        <p class="chat-right-msg">{{$replay->question}}</p>
                    </div>
                </div>
            </div>
            @endforeach
          
    
           
        </div>
        <form action="{{ route('instructor.replay') }}" method="post">
            @csrf
            <input type="hidden" name="course_id" value="{{ $question->course_id }}">
            <input type="hidden" name="user_id" value="{{ $question->user->id }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="instructor_id" value="{{ Auth::user()->id }}">

            <div class="chat-footer d-flex align-items-center">
                <div class="flex-grow-1 pe-2">
                    <div class="input-group">	<span class="input-group-text"><i class='bx bx-comment-add'></i></span>
                        <input type="text" name="question" class="form-control" placeholder="Type a message">
                    </div>
                </div>
                <div class="chat-footer-menu"> 
                    <button class="btn rounded-circle " type="submit" href="javascript:;"><i class='bx bxs-send'></i></button>
                
                </div>
            </div>
        </form>
        <!--start chat overlay-->
        <div class="overlay chat-toggle-btn-mobile"></div>
        <!--end chat overlay-->
    </div>
</div>

@endsection