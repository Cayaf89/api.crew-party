window.manage_errors = function (errors) {

    let $return = '';

    if ( typeof errors === 'string' ) {
        return errors;
    }

    if ( typeof errors === 'object' ) {


        for (const prop in errors) {

            if ( typeof errors[prop] === 'object' )

                for (const prop2 in errors[prop]) {

                    if ( typeof errors[prop][prop2] === 'object' )
                        for (const prop3 in errors[prop][prop2])
                            return errors[prop][prop2][prop3];

                    return errors[prop][prop2];
                }

            else
                return  errors[prop];
        }

        return $return;
    }

    return $return;
};
