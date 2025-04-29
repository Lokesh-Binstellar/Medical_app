@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mt-4">
    <h4>Add Medicine</h4>
    <form method="POST" action="{{ route('medicines.store') }}">
        @csrf

        <table class="table" id="medicine-table">
            <thead>
                <tr>
                    <th>Search Medicine</th>
                    <th>MRP</th>
                    <th>Final Amount</th>
                    <th>Discount %</th>
                    <th>Available</th>
                    <th>Substitute</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="medicine-body">
                <tr class="medicine-row">
                    <td>
                        <select class="form-control medicine_search" name="medician[0][medicine_id]"></select>
                        <input type="hidden" class="medicine_name" name="medician[0][medicine_name]">
                    </td>
                    
                    <td>
                        <input type="number" name="medician[0][mrp]" class="form-control mrp" step="0.01" placeholder="MRP">
                    </td>
                    <td>
                        <input type="number" name="medician[0][discount]" class="form-control discount" step="0.01" placeholder="Final Amount">
                    </td>
                    <td>
                        <input type="number" name="medician[0][discount_percent]" class="form-control discount_percent" step="0.01" placeholder="%">
                    </td>
                    <td>
                        <select name="medician[0][available]" class="form-control">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </td>
                    <td>
                        <select name="medician[0][is_substitute]" class="form-control">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">-</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="add-row" class="btn btn-primary">+</button>
        <button type="submit" class="btn btn-success mt-2">Save</button>
    </form>
</div>

<script>
function initSelect2($el) {
    $el.select2({
        placeholder: 'Search by name or salt',
        minimumInputLength: 0,
        ajax: {
            url: '{{ route("search.medicine") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term || '' };
            },
            processResults: function (data) {
                
                return { results: data };
            }
        }
    }).on('select2:select', function (e) {
        console.log("data",e);
        const selectedText = e.params.data.text;
        const $row = $(this).closest('tr');
        $row.find('.medicine_name').val(selectedText);
    });
}


$(document).ready(function () {
    let index = 1;

    // Initialize Select2 for the first row
    initSelect2($('.medicine_search'));

    $('#add-row').on('click', function () {
        const $newRow = $('.medicine-row').first().clone();

        // Reset input/select values
        $newRow.find('input').val('');
        $newRow.find('select').not('.medicine_search').val('yes');

        // Update input/select names with new index
        $newRow.find('select, input').each(function () {
            const name = $(this).attr('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName);
            }
        });

        // Remove previous select2 wrapper
        $newRow.find('.select2-container').remove();
        const $newSelect = $newRow.find('.medicine_search').clone().val('');
        $newRow.find('.medicine_search').replaceWith($newSelect);
        initSelect2($newSelect);

        $('#medicine-body').append($newRow);
        index++;
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        if ($('#medicine-body .medicine-row').length > 1) {
            $(this).closest('tr').remove();
        }
    });

// Auto calculate discount percent
$(document).on('input', '.mrp, .discount', function () {
    const $row = $(this).closest('tr');
    const mrp = parseFloat($row.find('.mrp').val()) || 0;
    const discountAmount = parseFloat($row.find('.discount').val()) || 0;
    if (mrp > 0 && discountAmount <= mrp) {
        const percent = ((mrp - discountAmount) / mrp) * 100;
        $row.find('.discount_percent').val(percent.toFixed(2));
    }
});

// Auto calculate final amount (after discount %) if MRP and % given
$(document).on('input', '.mrp, .discount_percent', function () {
    const $row = $(this).closest('tr');
    const mrp = parseFloat($row.find('.mrp').val()) || 0;
    const percent = parseFloat($row.find('.discount_percent').val()) || 0;
    if (mrp > 0 && percent >= 0 && percent <= 100) {
        const finalPrice = mrp - ((percent / 100) * mrp);
        $row.find('.discount').val(finalPrice.toFixed(2));
    }
});

});
</script>
@endsection
