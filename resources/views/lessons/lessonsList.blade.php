<div class="Card-Collection">
    <table class="table lessons-table table-hover">
        <tbody>
        @foreach($topic->lessons()->get() as $i => $lesson)
                <tr class="lesson-row">
                    <th scope="row">{{$i}}.</th>
                    <td onclick="window.document.location='{{url('lessons/' . $lesson->id)}}'">{{$lesson->title}}</td>
                    <td>14:32</td>
                </tr>
        @endforeach
        </tbody>
    </table>
</div>