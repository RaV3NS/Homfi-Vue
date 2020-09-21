<?php /** @var string $name */ ?>

<div class="panel panel-default">
    @php
        $newName = $name;
    @endphp
    <div class="panel-heading">
        {{$newName = isset(${preg_replace('/\_id/', '', $name) . 's'}) ? preg_replace('/\_id/', '', $name) : $name}}</div>
    <div class="panel-body">
        <p class="bg-danger"><strong>Old
                Value: </strong>
            {{$newName !== $name && ${$newName . 's'}->firstWhere('id', $values['old']) ? ${$newName . 's'}->firstWhere('id', $values['old'])->title : $values['old']}}
        </p>
        <p class="bg-success"><strong>New
                Value: </strong>
            {{$newName !== $name && ${$newName . 's'}->firstWhere('id', $values['new']) ? ${$newName . 's'}->firstWhere('id', $values['new'])->title : $values['new']}}
        </p>
    </div>
</div>