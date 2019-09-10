<!--sidebar-menu-->
<div id="sidebar">
  <a href="" class="visible-phone">
    <i class="icon icon-home"></i> Dashboard
  </a>
  <ul>
    <li class="active">
      <a href="{{ url('/admin/dashboard') }}">
        <i class="icon icon-home"></i> 
        <span>Dashboard</span>
      </a> 
    </li>
    <li>
      <a href="{{ url('admin/view-categories') }}">
        <i class="icon icon-th"></i> 
        <span>Categories</span>
      </a>
    </li>
    <li> 
      <a href="{{ url('admin/view-products') }}">
        <i class="icon icon-signal"></i> 
        <span>Products</span>
      </a> 
    </li>
  </ul>
</div>
<!--sidebar-menu-->