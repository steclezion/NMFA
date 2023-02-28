{{--------------------START Timeline ------------------}}
<div class="tab-pane fade" id="custom-tabs-three-timeline" role="tabpanel"
     aria-labelledby="custom-tabs-three-timeline-tab">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task Timeline</h3>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <!-- The time line -->
                    <div class="timeline">
                        <!-- timeline time label -->

                        <!-- /.timeline-label -->
                        <!-- timeline item -->

                        @foreach($tasks as $task)
                            <div>
                                @if($task->task_category=='Applying')
                                    <span class="fa fa-edit bg-blue"></span>
                                @elseif($task->task_category=='Variation')
                                    <span class="fa fa-book-reader bg-blue"></span>
                                @elseif($task->task_category=='Query')
                                    <i class="fas fa-envelope bg-red"></i>
                                @elseif($task->task_category=='Sample Testing')
                                    <i class="fas fa-eye-dropper bg-secondary"></i>
                                @elseif($task->task_category=='Assessment Report')
                                    <i class="fas fa-file-word bg-primary"></i>
                                @elseif($task->task_category=='Message')
                                    <i class="fas fa-bell bg-success"></i>
                                @elseif($task->task_category=='Extension')
                                    <i class="fas fa-user-clock bg-primary"></i>
                                @elseif($task->task_category=='Decision')
                                    <span class="fa fa-file bg-yellow"></span>
                                @elseif($task->task_category=='post-market')
                                    <span class="fa fa-shopping-basket bg-gradient-success"></span>
                                @else
                                    <i class="fas fa-user bg-green"></i>
                                @endif
                                <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i>
                                    <b>
                                        <?php
                                        $date = new DateTime($task->start_time);
                                        echo(($date->format('d-m-Y')));
                                        $date_started = $date->format('d-m-Y H:i:s');
                                        ?>
                                    </b>
                                </span>
                                    <span class="bg-yellow round-tabs">{{$date_started}} </span>
                                    <h3 class="timeline-header">

                                        <b class="text-blue">{{$task->task_activity_title}}</b>

                                    </h3>

                                    @if($task->content_detail===null || $task->content_detail==="")
                                    @else
                                        <div class="timeline-body">

                                           {{-- content details are inserted to db as a paragraph separated by fullstop.
                                           split (explode) them into sentences,  and write one sentence in one line
                                           (terminated by fullstop--}}
                                            <?php
                                            $sentences = explode('.', $task->content_detail);

                                            ?>
                                            @foreach($sentences as $sentence)
                                                @if($sentence != null)
                                                <span>{{$sentence.'.'}}</span><br/>
                                                @endif
                                            @endforeach


                                            {{--<br />
                                            @if($task->document_id===null || $task->document_id==="")
                                            @else
                                            <br /> <i class="fas fa-link mr-1"></i> File Attachment is
                                            available for this task.
                                            <a href="{{$doc_link = asset($task->document_id)}}"
                                                class="btn-default rounded-top rounded"> Open </a>
                                            @endif--}}

                                        </div>
                                    @endif

                                    <div class="timeline-footer">
                                        {{-- <a class="btn btn-primary btn-sm" href="{{ url('main_task/show/'.$task->id)}}">Read more</a>--}}

                                        <table class="table">

                                            <tbody>
                                            <tr data-widget="expandable-table" aria-expanded="false">
                                                <td>
                                                    <a class="btn btn-primary btn-sm">Read more</a>
                                                </td>
                                            </tr>
                                            <tr class="expandable-body">
                                                <td>


                                                    <div class="timeline-body">

                                                        <table class="table table-borderless table-sm">
                                                            <tbody>
                                                            {{-- @if($task->content_detail!=null || $task->content_detail!="")
                                                                 <tr>
                                                                     <td class="text-muted" width="20%">Detail</td>
                                                                     <td class="text-left text-bold">{{$task->content_detail}}</td>
                                                                 </tr>
                                                             @endif--}}
                                                            @if( $main_task->related_task !=null)
                                                                <tr>
                                                                    <td class="text-muted" width="20%">Phase</td>
                                                                    <td class="text-left text-bold">{{$main_task->related_task}}</td>
                                                                </tr>
                                                            @endif
                                                            @if( $task->task_category !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Category</td>
                                                                    <td class="text-left text-bold">{{$task->task_category}}</td>
                                                                </tr>
                                                            @endif

                                                            @if( $task->created_at !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Created at</td>
                                                                    <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                </tr>
                                                            @endif
                                                            @if( $task->updated_at !=null)
                                                                <tr>
                                                                    <td class="text-muted">Task Updated at</td>
                                                                    <td class="text-left text-bold">{{$task->created_at}}</td>
                                                                </tr>
                                                            @endif
                                                            @if($task->activity_status !=null)
                                                                <tr>
                                                                    <td class="text-muted">Status</td>
                                                                    <td class="text-left text-bold">
                                                                        @if($task->activity_status == 'Inprogress')
                                                                            <span class="badge bg-secondary">In-progress</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">{{$task->activity_status}}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                              {{-- @if($task->uploaded_document_id!=null)
                                                                   <tr>
                                                                       <td class="text-muted">Document</td>
                                                                       <td class="text-left text-bold">
                                                                           <a href="{{asset($document->path)}}"
                                                                              class="btn-default rounded-top rounded">
                                                                               Open </a>
                                                                       </td>
                                                                   </tr>
                                                               @endif--}}

                                                            </tbody>
                                                        </table>


                                                    </div>


                                                </td>


                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>

                    </div>
                </div>
                <!-- /.col -->
            </div>
        </div>
    </div>

</div>
{{--------------------END Timeline ------------------}}
