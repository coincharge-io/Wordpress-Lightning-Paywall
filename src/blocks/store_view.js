const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-store-view", {
    title: 'LP Video Store',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-video-paywall' ],
    edit:props =>{return <hr class="lnpw_pay__gutenberg_block_separator"></hr>},
    save:props => {return null}
});