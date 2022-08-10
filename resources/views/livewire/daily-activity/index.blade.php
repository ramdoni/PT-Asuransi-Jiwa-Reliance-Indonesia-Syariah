@section('sub-title', 'Index')
@section('title', 'Daily Activity')
<div class="row clearfix taskboard">
    <div class="col-lg-4 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <h2>Planned</h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#addcontact"><i class="icon-plus"></i></a></li>
                </ul>
            </div>
            <div class="body taskboard">
                <div class="dd" data-plugin="nestable">
                    <ol class="dd-list">
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">#L1008</div>
                            <div class="dd-content p-15">
                                <h5>Job title</h5>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                <ul class="list-unstyled team-info m-t-20 m-b-20">
                                    <li class="m-r-15"><small class="text-muted">Team</small></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar1.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar2.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar5.jpg" title="Avatar" alt="Avatar"></li>
                                </ul>
                                <hr>
                                <div class="action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                    <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                </div>
                            </div>
                        </li>                                    
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card progress_task">
            <div class="header">
                <h2>In progress</h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#addcontact"><i class="icon-plus"></i></a></li>
                </ul>
            </div>
            <div class="body taskboard">
                <div class="dd" data-plugin="nestable">
                    <ol class="dd-list">
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">#L1010</div>
                            <div class="dd-content p-15">
                                <h5>Job title</h5>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                <hr>
                                <div class="action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                    <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                </div>
                            </div>
                        </li>
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">#L1011</div>
                            <div class="dd-content p-15">
                                <h5>Job title</h5>
                                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero</p>
                                <ul class="list-unstyled team-info m-t-20 m-b-20">
                                    <li class="m-r-15"><small class="text-muted">Team</small></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar7.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar9.jpg" title="Avatar" alt="Avatar"></li>
                                </ul>
                                <hr>
                                <div class="action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                    <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card completed_task">
            <div class="header">
                <h2>Completed</h2>
                <ul class="header-dropdown">
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#addcontact"><i class="icon-plus"></i></a></li>
                </ul>
            </div>
            <div class="body taskboard">
                <div class="dd" data-plugin="nestable">
                    <ol class="dd-list">                                   
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">#L1005</div>
                            <div class="dd-content p-15">
                                <h5>Job title</h5>
                                <p>If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                                <ul class="list-unstyled team-info m-t-20 m-b-20">
                                    <li class="m-r-15"><small class="text-muted">Team</small></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar4.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar5.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar6.jpg" title="Avatar" alt="Avatar"></li>
                                    <li><img src="http://lucidaraveladmin.local/assets/img/xs/avatar8.jpg" title="Avatar" alt="Avatar"></li>
                                </ul>
                                <hr>
                                <div class="action">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                    <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>