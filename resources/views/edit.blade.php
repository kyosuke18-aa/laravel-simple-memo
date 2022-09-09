@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        メモ編集
        <form id="delete-form" action="{{route('destroy')}}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{$edit_memo[0]['id']}}">
            <i class="fa-solid fa-trash mr-3" onclick="deleteHandle(event);"></i>
        </form>
    </div>
    <div class="card-body">
        <!-- route('store') とかくと -->
        <form class="card-text my-card-body" action="{{route('update')}}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{$edit_memo[0]['id']}}" />

            <div class="form-group">
                <label for="exampleFormControlTextarea1" class="form-label"></label>
                <textarea name="content" class="form-control" rows="3" placeholder="メモを入力">{{$edit_memo[0]['content']}}
                </textarea>
            </div>
            @error('content')
            <div class="alert alert-danger">メモ内容を入力してください!</div>
            @enderror

            @foreach($tags as $t)
            <div class="form-check form-check-inline mb-3">
                <!-- 三項演算子　→ if文を一行で書く方法 -->
                <!-- もしinclude_tagsにループで回っているタグのidが含まれれば、checkedとかく -->
                <input class="form-check-input" type="checkbox" name="tags[]" id="{{$t['id']}}" value="{{$t['id']}}" {{in_array($t['id'], $include_tags) ? 'checked' : '' }}>
                <label class="form-check-label" for="{{$t['id']}}">{{$t['name']}}</label>
            </div>
            @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを入力">
            <button type="submit" class="btn btn-primary">更新</button>
        </form>
    </div>
</div>




@endsection