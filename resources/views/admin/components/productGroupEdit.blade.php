<input type="hidden" name="product_group_id" value="{{$group->id}}">
<div class="row">
    <div class="col-lg-5">
        <div class="form-group m-form__group">
            <label for="admin_name">
                Product
            </label>
            <input type="text" class="form-control m-input m-input--air" value="{{$productName}}" placeholder="Product Name" readonly>
        </div>
        <div class="form-group m-form__group">
            <label for="admin_name">
                Group Name
            </label>
            <input type="text" class="form-control m-input m-input--air" name="_group_name" value="{{$group->group_name}}" placeholder="Group Name" required>
        </div>
        <div class="form-group m-form__group" style="margin-bottom: 0;">
            <label for="admin_name">
                Type
            </label>
            <select class="form-control m-bootstrap-select m-input--air m_selectpicker" name="_group_type">
                <option value="single" @if ($group->type == 'single') selected @endif>Single Select</option>
                <option value="multi" @if ($group->type == 'multi') selected @endif>Multi Select</option>
            </select>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-group m-form__group" style="margin-bottom: 0;">
            <label for="admin_name">
                Extras
            </label>
            <div class="extraTable-container">
                <table class="table table-bordered m-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($extras as $extra)
                            <tr>
                                <th scope="row" style="width: 42px;">
                                    <span style="position: relative; width: 30px;">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--success m-checkbox-single">
                                            <input type="checkbox" @if (in_array($extra->id, $groupExtras)) checked @endif class="product_extra_select_tag" name="extraIds[]" value="{{$extra->id}}">
                                            <span></span>
                                        </label>
                                    </span>
                                </th>
                                <td>{{$extra->extra_name}}</td>
                                <td>{{$extra->extra_price}} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
