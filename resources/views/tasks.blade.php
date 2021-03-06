@extends('layouts.app')

@section('bodyClass')
tasks @endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="projects-panel">
                <h2 class="panel__title">My projects</h2>
                <!-- New project form -->
                <form class="form" action="/api/projects" method="post">
                    {{ csrf_field() }}
                    <fieldset class="form__small">
                        <label for="newProject" class="form__label">Add project</label>
                        <input class="form__input" type="text" name="projectName" value="" placeholder="Your project name" id="newProject">
                        @if ($errors->has('projectName'))
                            <div>
                                <?php echo $errors->first('projectName'); ?>
                            </div>
                        @endif
                        <input class="form__button primary" type="submit" value="Add">
                    </fieldset>
                </form>
                <!-- Projects list -->
                @if (count($projects) == 0)
                    <p class="no-item">
                        You don't have any projects yet but feel free to create some!
                    </p>
                @else
                    <ul class="list">
                            @foreach ($projects as $project)
                                <li class="list__item">
                                    <a class="list__item__item" href="?project={{ $project->id }}">
                                        <span class="project-icon" style="background-color: {{ $project->color }};"></span>
                                        {{ $project->name }}
                                    </a>
                                    <a class="list__item__delete action-icon" href="/projects/{{ $project->id }}/confirmdelete">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                    <a class="list__item__edit action-icon" href="/api/projects/{{ $project->id }}/edit">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @endforeach
                            <li class="list__item">
                                <span class="project-icon" ></span>
                                <a href="/tasks">All</a>
                            </li>
                    </ul>
                @endif
            </div>
            <div class="tags-panel" id="tags">
                <h2 class="panel__title">My tags</h2>
                <!-- New tag form -->
                <form class="form" action="/api/tags" method="post">
                    {{ csrf_field() }}
                    <fieldset class="form__small">
                        <label class="form__label" for="tagName">Add tag</label>
                        <input class="form__input" type="text" name="tagName" placeholder="Your tag (ex: design)" value="" id="tagName">
                        @if ($errors->has('tagName'))
                            <div>
                                <?php echo $errors->first('tagName'); ?>
                            </div>
                        @endif
                        <input class="form__button primary" type="submit" value="Add">
                    </fieldset>
                </form>
                <!-- Tags list -->
                <form class="" action="/tasks" method="get">
                    <div>
                        @if (count($tags) == 0)
                            <p>
                                You don't have any tags yet but feel free to create some!
                            </p>
                        @else
                            <ul class="list">
                                @foreach ($tags as $tag)
                                <li class="list__item">
                                    <div class="checkbox">
                                        <label for="tag{{ $tag->id }}">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </label>
                                    </div>
                                    <a class="list__item__delete action-icon" href="/tags/{{ $tag->id }}/confirmdelete">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                    <a class="list__item__edit action-icon" href="/api/tags/{{ $tag->id }}/edit">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </li>
                                @endforeach
                                <!-- <fieldset class="form__small"> -->
                                    <!-- <span class="form__info">Check the tags you want to sort your tasks by</span> -->
                                    <input type="submit" value="Sort" class="form__button primary">
                                <!-- </fieldset> -->
                            </ul>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="tasks-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">My tasks</h3>
                    <!-- Sort by date -->
                    <ul class="nav nav-pills">
                        <li><a href="/tasks">All</a></li>
                        <li><a href="?minDate={{ \Carbon\Carbon::today()->toDateString() }}&amp;maxDate={{ \Carbon\Carbon::today()->addDay()->toDateString() }}">Today</a></li>
                        <li><a href="?minDate={{ \Carbon\Carbon::today()->toDateString() }}&amp;maxDate={{ \Carbon\Carbon::today()->addWeek()->toDateString() }}">Next 7 days</a></li>
                        <li><a href="?minDate={{ \Carbon\Carbon::today()->toDateString() }}&amp;maxDate={{ \Carbon\Carbon::today()->addMonth()->toDateString() }}">Within one month</a></li>
                    </ul>
                </div>

                <div class="panel-body">
                    <!-- New task form -->
                    <form class="row no-js" action="/api/tasks" method="post" id="newTaskForm">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group col-md-4">
                                <!-- Add task name (mandority) -->
                                <label for="newTask">New task:</label>
                                <input class="form-control" type="text" name="body" value="" id="newTask">
                                @if ($errors->has('body'))
                                    <div>
                                        <?php echo $errors->first('body'); ?>
                                    </div>
                                @endif
                            </div>
                            <!-- Add due date (non mandatory) -->
                            <div class="form-group col-md-4">
                                <label for="dueDate">Add due date (yyyy-mm-dd):</label>
                                <input class="form-control" type="text" name="dueDate" id="dueDate">
                                @if ($errors->has('dueDate'))
                                    <div>
                                        <?php echo $errors->first('dueDate'); ?>
                                    </div>
                                @endif
                            </div>
                            <!-- Add project (non mandatory) -->
                            <div class="form-group col-md-4">
                                <label for="project">Assign project:</label>
                                <select class="form-control" id="project" name="project">
                                    <option value="">(None)</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Add tags (non mandatory) -->
                            @if(count($tags) == 0)
                                <p>
                                    <a href="#tags">No tags yet, add some!</a>
                                </p>
                            @else
                                <div class="form-group col-md-12">
                                    <label class="col-md-12">Add tag(s):</label>
                                    @foreach ($tags as $tag)
                                        <label for="newtag{{ $tag->id }}">
                                            <input type="checkbox" name="tags[]" id="newtag{{ $tag->id }}" value="{{ $tag->id }}" class="newTaskTags">
                                            {{ $tag->name }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            <div class="form-group col-md-12">
                                <input class="btn btn-primary" type="submit" value="Add new task">
                            </div>
                        </fieldset>
                    </form>
                    <!-- Tasks list -->
                    @if(count($tasks) == 0)
                        <p>
                            No task to display yet.
                        </p>
                    @else
                        <form class="no-js" action="/tasks" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <fieldset>
                                <ul class="list-group">
                                    @foreach ($tasks as $task)
                                        <li class="form-group list-group-item row">
                                            <input type="hidden" name="{{ $task->id }}" value="0">
                                            <div class="col-md-3">
                                                <button class="btn btn-default" type="submit" name="{{ 'delete-' . $task->id }}">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                                <input class="btn btn-default" type="submit" name="{{ 'edit-' . $task->id }}" value="edit">
                                            </div>
                                            <div class="checkbox col-md-9">
                                                <label for="{{ $task->id }}"><input type="checkbox" name="{{ $task->id }}" id="{{ $task->id }}" value="1" {{ $task->completed == 1 ? 'checked="checked"' : '' }}>{{ $task->body }} {{ $task->due_date ? '(due ' . $task->due_date->diffForHumans() . ')' : '' }}</label>
                                                <!-- Display tags -->
                                                <p>
                                                    @foreach ($task->tags as $tag)
                                                        <a href="/tasks?tags[]={{ $tag->id }}">#{{ $tag->name }}</a>
                                                    @endforeach
                                                </p>
                                                <!-- Display project -->
                                                @if($task->project()->first())
                                                    <p>
                                                        <?php $project = $task->project()->first(); ?>
                                                        <span style="display: inline-block; width: 16px; height: 16px; border-radius: 50%; background-color: {{ $project->color }}; margin-right: 10px; vertical-align: middle;"></span>
                                                        <a href="?project={{ $task->project_id }}">{{ $project->name }}</a>
                                                    </p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </fieldset>
                            <input class="btn btn-primary" type="submit" value="Save changes">
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<template id="taskDisplay">
    <li class="form-group list-group-item row">
        <input type="hidden" name="{{ $task->id }}" value="0">
        <div class="col-md-3">
            <button class="btn btn-default" type="submit" name="{{ 'delete-' . $task->id }}">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
            <input class="btn btn-default" type="submit" name="{{ 'edit-' . $task->id }}" value="edit">
        </div>
        <div class="checkbox col-md-9">
            <label for="{{ $task->id }}"><input type="checkbox" name="{{ $task->id }}" id="{{ $task->id }}" value="1" {{ $task->completed == 1 ? 'checked="checked"' : '' }}>{{ $task->body }} {{ $task->due_date ? '(due ' . $task->due_date->diffForHumans() . ')' : '' }}</label>
            <!-- Display tags -->
            <p>
                @foreach ($task->tags as $tag)
                    <a href="/tasks?tags[]={{ $tag->id }}">#{{ $tag->name }}</a>
                @endforeach
            </p>
            <!-- Display project -->
            @if($task->project()->first())
                <p>
                    <?php $project = $task->project()->first(); ?>
                    <span style="display: inline-block; width: 16px; height: 16px; border-radius: 50%; background-color: {{ $project->color }}; margin-right: 10px; vertical-align: middle;"></span>
                    <a href="?project={{ $task->project_id }}">{{ $project->name }}</a>
                </p>
            @endif
        </div>
    </li>
</template>

@endsection
