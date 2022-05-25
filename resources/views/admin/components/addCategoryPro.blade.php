@foreach ($products as $key => $product)
    <tr>
        <td style="width: 42px;">
            <span style="position: relative; width: 30px;">
                <label class="m-checkbox m-checkbox--solid m-checkbox--success m-checkbox-single">
                    <input type="checkbox" class="category_products_select_tag" name="productIds[]" value="{{$product->id}}">
                    <span></span>
                </label>
            </span>
        </td>
        <td style="width: 100px;"><img src="/uploads/products/{{$product->pro_image}}" style="width: 100%;" alt=""></td>
        <td>{{$product->pro_name}}</td>
        <td>{{$product->pro_price}} €</td>
    </tr>
@endforeach
