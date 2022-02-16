/**
 * File: shopwp-filters.js
 * Author: Sebastian Fehr
 * Desc: filters for the shopwp plugin
 * 
 */

let SHUFFLED = false;

/* manage tags in filter */
wp.hooks.addFilter(
    'storefront.availableTags',
    'shopwp',
    ( tags ) => {
          
        const tagEntries = tags.map( ( tag ) => {
            const conditions = [ 'use', 'color', 'size' ]
            if( ! conditions.some( el => tag.includes( el ) ) ){ return }
            const category = tag.split( '?' )[ 0 ]
            const label = tag.split( '?' )[ 1 ]
            return tag.label = label
        })

        const filteredTags = tagEntries.filter( ( el ) => el !== undefined )

        return filteredTags
    }
)


/* implement random shuffle */
wp.hooks.addAction(
    'on.afterPayloadUpdate', 
    'shopwp', 
    ( itemsState ) => {
        //console.log( 'on.afterPayloadUpdate', itemsState )
        if( SHUFFLED ) { return }
        setTimeout(() => {
            let list = document.querySelector( '.wps-items-list' )
            list.style.transition = 'opacity 2s ease-in-out'
            list.style.opacity = 0
            for ( var i = list.children.length; i >= 0; i -- ) {
                list.appendChild( list.children[ Math.random() * i | 0 ] )
            }
            list.style.opacity = 1;
            SHUFFLED = true;
       }, 500 );
})