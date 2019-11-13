require.config = ({
    map: {
        '*': {
            'cookiecode/jquery': 'js/jquery-1.11',
            'owlcarousel': 'js/owl-carousel/owlcarousel'
        }
    },
    paths: {
        'owlcarousel': "AvvyTest_FirstAvvysModule/js/owlcarousel"
    },
    shim: {
        'owlcarousel': {
            deps: ['jquery']
        }
    }
});
