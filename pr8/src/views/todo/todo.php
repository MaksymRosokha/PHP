<h1>To do list</h1>

<br>
<br>
<button id="btn-create-new-task">Create new task</button>
<button>View your profile</button>
<Label>Sort by status: <select>
        <option>all</option>
        <option>todo</option>
        <option>in progress</option>
        <option>complete</option>
    </select></Label>
<br>
<br>
<br>
<div id="div-create-new-task">

    <label for="contentOfTask">Content: </label><textarea id="contentOfCreateTask"></textarea>
    <br>
    <button id="btn-create">Create</button>
    <br>
    <br>
    <br>
</div>

<table id="table_tasks">
    <tr class="table-row">
        <th>Content</th>
        <th>Date of writing</th>
        <th>Date of Editing</th>
        <th>Status</th>
    </tr>
    <?php
    foreach ($tasks as $task) { ?>
        <tr class="table-row">
            <td><?php
                echo $task->getContent(); ?></td>
            <td><?php
                echo $task->getDateOfWriting(); ?></td>
            <td><?php
                echo $task->getDateOfEditing(); ?></td>
            <td><?php
                echo $task->getStatus(); ?></td>
            <td>Change status: <select>
                    <option <?php
                    if ($task->getStatus() === 'todo') { ?> selected <?php
                    } ?> >todo
                    </option>
                    <option <?php
                    if ($task->getStatus() === 'in progress') { ?> selected <?php
                    } ?> >in progress
                    </option>
                    <option <?php
                    if ($task->getStatus() === 'complete') { ?> selected <?php
                    } ?> >complete
                    </option>
                </select></td>
            <td>
                <button>Edit</button>
            </td>
            <td>
                <button id="btnDelete<?php
                echo $task->getId(); ?>" class="btn-delete">Delete
                </button>
            </td>
        </tr>
    <?php
    } ?>

</table>