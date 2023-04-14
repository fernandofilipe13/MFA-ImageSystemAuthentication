function closeFunction() {
    var imageCont = document.getElementById("imageContainer");
    imageCont.style.display = "none";
    document.getElementById("imagesList").style.display = "block";
}



$(document).on("click", ".imageListDisplay", function() {
    $('#imagesList').css("display", 'none')
    $('#imageContainer').css("display", 'block') // Get the expanded image
    $('#expandedImg').css("width", '540px')
    $('#expandedImg').css("height", '540px')
    $('#expandedImg').attr('src', $(this).attr('src')) // Use the same src in the expanded image as the image being clicked on from the grid
    $('#closeImage').css("display", 'flex') //Close Image Button
    $('#chooseImage').css("display", 'flex') //Choose Image Button

})

$(document).on("click", ".btn-generate-confirm", function() {
    $.ajax({
        type: 'POST',
        url: './server.php',
        data: {
            "def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f": 'def50200de2a934ac74e094ae30171198f2cb32d761749f081ff54edc78358870cefb77734e608b11c1e9f0e93372af02f5abb3320e03ba1e7658c30098522585a54ce7360d4f7f4e3ee26789d2124d4b195355d03bff9fce5e1ec5b86ae7cc9b070d352d5',
        },
        success: function(data) {

            $('#imagesList').html(data)
        }
    })
})

$(document).ready(function() {
    var orderSelectedId = [];
    var order;

    $(document).on("click", '.image', function() {
        $(this).toggleClass('imgSelect');


        if ($(this).hasClass('imgSelect')) {
            $(this).parent().append('<div class="centered"></div>');
            $('.imageGrid').attr('data-value', parseInt($('.imageGrid').attr('data-value')) + 1)

            order = parseInt($('.imageGrid').attr('data-value'))
            $(this).parent().find('.centered').html(order)
            $(this).attr('data-order', order)

            orderSelectedId.push($(this).attr('id'))
        } else {
            order = parseInt($('.imageGrid').attr('data-value')) - 1
            $(this).attr('data-order', '')
            $(this).parent().find('.centered').remove();
            $('.imageGrid').attr('data-value', parseInt($('.imageGrid').attr('data-value')) - 1)
                // const index = orderSelected.indexOf(parseInt($(this).attr('data-order')));
                // orderSelected.splice(index, 1);

            const i = orderSelectedId.indexOf($(this).attr('id'));
            orderSelectedId.splice(i, 1);
        }

        orderSelectedId.forEach(element => {
            const numInArr = orderSelectedId.indexOf(element) + 1;
            $('#' + element).attr('data-order', numInArr)
            $('#' + element).parent().find('.centered').html(numInArr)

        });

        var value = parseInt($('.imageGrid').attr('data-value'));

        if (value >= 4 && value <= 5) {
            var attr = $('.btn-confirm').attr('disabled');
            if (typeof attr !== 'undefined' && attr !== false) {
                $('.btn-confirm').addClass('btn-success').removeAttr('disabled')
                $('.errorSelectection').addClass('hide')

            }
        } else {
            $('.errorSelectection').removeClass('hide')
            $('.btn-confirm').removeClass('btn-success').attr('disabled', true)
        }
    })

    $(document).on("click", '.btn-confirm', function() {
        var value = parseInt($('.imageGrid').attr('data-value'));
        if (value < 4 || value > 5) {
            Swal.fire({
                icon: 'error',
                title: 'Incorrect number of selected images',
            })
            return;
        };

        var imagesValues = [];
        $('.image').each(function(index) {
            $(this).attr('id', 'item-' + (index + 1));
            imagesValues.push({
                'id': $(this).attr('id'),
                'value': $(this).attr('data-value')
            })
        });

        var imageSelect = [];
        $('.imgSelect').each(function(i, obj) {
            imageSelect.push($(obj).attr('data-value'));
        });

        if (imageSelect.length < 4 || imageSelect.length > 5) {
            Swal.fire({
                icon: 'error',
                title: 'Incorrect number of selected images',
            })
            return;
        };

        $.ajax({
            type: 'POST',
            url: './server.php',
            data: {
                "def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f": 'def5020013e1e6c8236a4eef112528d5ac014d3a32963ad0ded5b5df15b760cb7f72a6e9b4979d97cb752467942be350520ea7e32f30c9d58cb8ea8e523b27f760360426674e9a1395ca7aa05bf681c7b8846ea39da3cfd5268120e70e3079b34b730e',
                "def5020091438761f79acaa78b56cf5061dc38e1f38d36c7ec1cc9bb6787733421291568b072e9895884662ca209bfe778bbbbfe5625df3d2472d0ec2b3626e68618d71572f43b43c7b3a6f94c64f4c0f28403ded4e3f2a89a": imageSelect,
                "def502000c356afad961652d2bedf0ec3d9dcfb0f3d837c5c2639bccb76831cdac1a8edebb7c4bca3c0f8cbd8665239cde55b765ece0a7715c32a07e675f73ee2c90d67818b49ae3212e4373b27a4a5ec31e59b6dd8bf2322913bf6f210c": orderSelectedId,
                "def502002f67e4a4eb1114046d026a84595bf3751448e0d89168ec35d834993b00c4f2779952dbf91a5b8fb663e143ca7be46fd0e7cae0876b0ef0e56a8778d55a0a919ba2355b936cf1c58b4505b84130510501e632c5032d8ddca76f041e22ff2708": imagesValues,
            },
            success: function(data) {;
                Swal.fire({
                    title: 'Security Code updated!',
                    text: 'Now you must continue.',
                    icon: 'success',
                    timer: 2000,
                })

                var debug = false;
                if (debug == false) {
                    var result = $.parseJSON(data);

                    if (result.status == true) {

                        $('#setupAuth').html('');
                        $('#imgAuth').html('');
                        $('#tryAuth').removeClass('hide').html(result.divElement);
                        $('#titleStep').html('<h3 class="align"><b>THIRD STEP</b></h3>');
                        orderSelectedId = [];
                        imagesValues = [];
                        imageSelect = [];
                        order = '';
                    } else {
                        // Swal.fire({
                        //     icon: 'error',
                        //     title: 'Wrong Image Selected',
                        //     text: 'You have only X attempts.',
                        //     footer: '<a href="">Why do I have this issue?</a>'
                        // })
                    }
                }
            }
        })
    })

    $(document).on("click", '.btn-try-confirm', function() {
        var imagesValues = [];
        $('.image').each(function(index) {
            $(this).attr('id', 'item-' + (index + 1));
            imagesValues.push({
                'id': $(this).attr('id'),
                'value': $(this).attr('data-value')
            })
        });

        var imageSelect = [];
        $('.imgSelect').each(function(i, obj) {
            imageSelect.push($(obj).attr('data-value'));
        });
        $.ajax({
            type: 'POST',
            url: './server.php',
            data: {
                "def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f": 'def50200b297b50622aa712faf56dd950665c05564d159530b757d3a0693871d7a7f549cf13ca9e4e422cdeef99d47d2210c026e7a9cad5e02cd24cab19cb67c1b0e4cd7eee0cf7f3361241e13674dd5265f99eefb6cc37238c383e591a07ffe3715',
                "def5020091438761f79acaa78b56cf5061dc38e1f38d36c7ec1cc9bb6787733421291568b072e9895884662ca209bfe778bbbbfe5625df3d2472d0ec2b3626e68618d71572f43b43c7b3a6f94c64f4c0f28403ded4e3f2a89a": imageSelect,
                "def502000c356afad961652d2bedf0ec3d9dcfb0f3d837c5c2639bccb76831cdac1a8edebb7c4bca3c0f8cbd8665239cde55b765ece0a7715c32a07e675f73ee2c90d67818b49ae3212e4373b27a4a5ec31e59b6dd8bf2322913bf6f210c": orderSelectedId,
                "def502002f67e4a4eb1114046d026a84595bf3751448e0d89168ec35d834993b00c4f2779952dbf91a5b8fb663e143ca7be46fd0e7cae0876b0ef0e56a8778d55a0a919ba2355b936cf1c58b4505b84130510501e632c5032d8ddca76f041e22ff2708": imagesValues,
            },
            success: function(data) {
                try {
                    var result = $.parseJSON(data);
                    if (result.status == true && result.permission == true) {
                        Swal.fire({
                            title: 'The security code is correct!',
                            text: 'You are logged in!',
                            icon: 'success',
                            timer: 2000,
                        }).then(() => {
                            if (result.hasBackup == false) {
                                Swal.fire({
                                    title: 'Save your backup code!',
                                    text: result.backup,
                                    icon: 'warning',
                                    footer: '<a style="color:black;" href="">Why do I need this backup code?</a>',
                                    showCancelButton: true,
                                    confirmButtonText: 'I saved it in a safe place',
                                    showCancelButton: false,
                                }).then(() => {
                                    location.href = './index.php';
                                })
                            } else {
                                location.href = './index.php';
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: result.title,
                            text: 'You have only ' + result.leftAttempts + ' attempts.',
                            footer: '<a style="color:black;" href="">Why do I have this issue?</a>'
                        })
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: result.title,
                        text: 'You have only ' + result.leftAttempts + ' attempts.',
                        footer: '<a style="color:black;" href="">Why do I have this issue?</a>'
                    })
                    return false;
                }

            }
        })
    })

    $(document).on("click", '#chooseImage', function() {


        Swal.fire({
            icon: 'question',
            title: 'Choose this Image?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            imageUrl: $(this).attr('src'),
            imageHeight: 270,
            imageAlt: 'Image choosen',

        }).then((result) => {
            if (result.isConfirmed) {


                var imageChose = $('#expandedImg').attr('src')
                $.ajax({
                    type: 'POST',
                    url: './server.php',
                    data: {
                        "def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f": 'def50200fcb9e3de98b8ff0d0259a4ee66e41ccb0e2fabf56cd884983098127e5c4497f482bfe75b1ca4de5e78dcae7e82862624386d6144de02e2fffa86052c517e4672eb3857ab612a966a64728bc8bc0dad50e6e2a10c654ac95d34a0b6',
                        "def50200203a3becbe420f8b5d704fc94974fa1289b1e3f42b29bf802ffb03994bd7ad71c83178afef4e4aaffb63face13e05d537ee4e61f32f5b2672a00101504340f669319893794e33f488aaac3769a80c58e5fc0d1e10c": imageChose,
                    },
                    success: function(data) {

                        try {

                            var result = $.parseJSON(data);

                            if (result.status == true) {

                                $('#imgAuth').html('');
                                $('#setupAuth').removeClass('hide').html(result.divElement);
                                $('#tryAuth').html('');
                                $('#titleStep').html('<h3 class="align"><b>SECOND STEP</b></h3><p>Create a security code by selecting the parts of the images you want! Order matters!</p>');

                                Swal.fire({
                                    title: 'Done!',
                                    text: 'You can continue!',
                                    icon: 'success',
                                    timer: 5000,
                                })
                            } else {
                                // Swal.fire({
                                //     icon: 'error',
                                //     title: 'Wrong Image Selected',
                                //     text: 'You have only X attempts.',
                                //     footer: '<a href="">Why do I have this issue?</a>'
                                // })
                            }
                        } catch (e) {
                            return false;
                        }

                    }
                })


            }
        })
    })



    $('.btn-tutorial').on('click', function(e) {
        Swal.fire({
            title: 'Image System Authentication - Tutorial (1/3)',
            html: 'Select the image to remember later',
            showCancelButton: false,
            confirmButtonText: 'Next',
            confirmButtonColor: '#3085d6',
            icon: 'info',
            timer: 10000,
        }).then(() => {
            Swal.fire({
                title: 'Image System Authentication - Tutorial (2/3)',
                html: 'Select the parts of the image to become your security code. Order matters!',
                showCancelButton: false,
                confirmButtonText: 'Next',
                confirmButtonColor: '#3085d6',
                icon: 'info',
                timer: 10000,
            }).then(() => {
                Swal.fire({
                    title: 'Image System Authentication - Tutorial (3/3)',
                    html: 'Test your authentication!',
                    showCancelButton: false,
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#3085d6',
                    icon: 'success',
                    timer: 10000,
                })
            })
        })
    })

    $(document).on("click", '.imageAuth', function() {
        Swal.fire({
            icon: 'question',
            title: 'Select this Image?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            imageUrl: $(this).attr('src'),
            imageHeight: 270,
            imageAlt: 'Image Selected',

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './server.php',
                    data: {
                        "def50200b09dc5f62ae5c2f74a73ec8d2d82de427ba3de55480b155e242c6dae0cbc560c0ec9e8e13939cf0e1b4c35b74047b9603514768b97266637003baf1739ededbc84d662d1ce59b66057e940a0837f57236b8c64dffb2f": 'def502009f4c92834d11c9fabb69a0ebfcdec11d3e3917f115be56c1372b349547eadda07f6ebb9d8ba3d10c7e3a4dca8b36b7dda7bedd398ffc0a5af1551b51cd8b44d4fac5fca789e6148807d6b1bbafd2cc396fe2431e57c14cdf84ead1fc23398a2be3f374b9',
                        "def50200d589d8a621d04936986390bd05acb2082febfa59e71621a1226809e2aa7207ac5112db2edfebf9c6e1c3096784cbc651c333636e7d938bcaf03c0c0adee7de3ada3e9a790dd976baa1f4d04edd99743fee76a814b61ce18b7749d53cc8d6dfe39de8": $(this).attr('src'),
                    },
                    success: function(data) {

                        try {
                            var result = $.parseJSON(data);
                            if (result.status == true) {
                                Swal.fire({
                                    title: 'Correct!',
                                    text: 'You can continue!',
                                    icon: 'success',
                                    timer: 1000,
                                })
                                $('#tryAuth').html(result.divElement);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Wrong Image Selected',
                                    text: 'You have only ' + result.leftAttempts + ' attempts.',
                                    footer: '<a style="color:black;"  href="">Why do I have this issue?</a>'
                                })
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Wrong Image Selected',
                                text: 'You have only ' + result.leftAttempts + ' attempts.',
                                footer: '<a style="color:black;"  href="">Why do I have this issue?</a>'
                            })
                            return false;
                        }
                    }
                })

            }
        })

    })

});