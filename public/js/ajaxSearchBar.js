$(document).ready(function (){
    $('#search-user').keyup(function (){
        $('#result-search').html('');

        var utilisateur = $(this).val();

        if(utilisateur != ""){
            $.ajax({
                url: '/amis',
                type: 'GET',
                dataType: 'json',
                data: 'user=' + encodeURIComponent(utilisateur),
                async: true,
                success: function (data){
                    if(data != ""){
                        console.log(data);
                        concatdata = '';
                        for(i in data){
                            concatdata += '<div id="invite-heading-' + data[i]['id'] + '">';
                            concatdata += '<button class="btn-outline btn-block box box--small collapsed" type="button" data-toggle="collapse" data-target="#invite-collapse-' + data[i]['id'] + '" aria-expanded="false" style="background-color: var(--gray-lightest)">';
                            if(data[i]['avatar'] == null){
                                concatdata += '<img src="/images/avatar/default_avatar.jpg" alt="Avatar" class="m-r-5 avatar avatar--large">';
                            }
                            else {
                                concatdata += '<img src="' + data[i]['avatar'] + '" alt="Avatar" class="m-r-5 avatar avatar--large">';
                            }
                            concatdata += '<span class="text-large text-bold">' + data[i]['username'] + '</span>';
                            concatdata += '</button>';
                            concatdata += '</div>';
                            concatdata += '<form method="get"><div id="invite-collapse-' + data[i]['id'] + '" class="collapse" aria-labelledby="invite-heading-' + data[i]['id'] + '" data-parent="#invite-accordion">'
                            concatdata += '<button class="btn-block btn--submit" type="submit" name="invite" value="' + data[i]['id'] + '">Inviter</button>';
                            concatdata += '<div class="btn-block btn--warning" data-toggle="modal" data-target="#invite-modal-blocked-' + data[i]['id'] + '">Bloquer</div>';
                            concatdata += '<div class="modal" id="invite-modal-blocked-' + data[i]['id'] + '" tabIndex="-10" role="dialog"data-backdrop="static" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content box--white"><div class="modal-body text-center text-bold alert-danger">Vous êtes sur le point de bloquer ' + data[i]['username'] + '. Vous ne pourrez plus communiquer avec ni l\'inviter de nouveau. cette action est irreversible.</div><div class="modal-footer"><button class="btn-block btn--remove" type="submit" name="blocked" value="' + data[i]['id'] + '">Je suis sûr de vouloir bloquer ' + data[i]['username'] + '</button><div class="btn-block btn--accept" data-dismiss="modal">Annuler</div></div></div></div></div>';
                            concatdata += '</div></form>';

                        }
                        document.getElementById('result-search').innerHTML = concatdata;
                    }else {
                        document.getElementById('result-search').innerHTML = '<div class="box text-large text-center text-bold" style="background-color: var(--gray-lightest)">Aucun résultat</div>';
                    }
                },
                error: function (xhr, textStatus, errorThrown){
                    document.getElementById('result-search').innerHTML = '<div class="box text-large text-center text-bold" style="background-color: var(--gray-lightest)">Une erreur est survenue</div>';
                }
            });
        }
    });
});