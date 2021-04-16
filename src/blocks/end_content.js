const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-end-block", {
    title: 'LP End Paid Text Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-paywall' ],
    edit:props =>{return <hr class="lnpw_pay__gutenberg_block_separator"></hr>},
    save:props => {return null}
});