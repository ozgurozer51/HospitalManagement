
<label class="form-label"> Kullanıcı Ara</label>
<select class='form-control ara' type='text' placeholder='ara' />

</select>

<script>
    $(".ara").select2({
        tags: true,
        multiple: false,
        tokenSeparators: [',', ' '],
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: "ajax/123.php",
            dataType: "json",
            type: "GET",
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name_surname,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
</script>
