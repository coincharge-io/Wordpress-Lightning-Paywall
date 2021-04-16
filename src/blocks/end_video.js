const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-end-video-block", {
    title: 'LP End Paid Video Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-video-paywall' ],
    edit:props =>{return <hr class="lnpw_pay__gutenberg_block_separator"></hr>},
    save:props => {return null}
});