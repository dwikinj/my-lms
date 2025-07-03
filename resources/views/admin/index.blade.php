@extends('admin.admin_dashboard')
@section('admin')
    
<div class="page-content">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Orders</p>
                        <h4 class="my-1 text-info">{{ $totalOrders }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
                    </div>
                </div>
            </div>
         </div>
       </div>
       <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Total Revenue</p>
                       <h4 class="my-1 text-danger">${{ $totalRevenue }}</h4>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
                   </div>
               </div>
           </div>
        </div>
      </div>
      <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-success">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Total Instructor</p>
                       <h4 class="my-1 text-success">{{ $totalInstructors }}</h4>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
                   </div>
               </div>
           </div>
        </div>
      </div>
      <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-warning">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Total Students</p>
                       <h4 class="my-1 text-warning">{{ $totalStudents }}</h4>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
                   </div>
               </div>
           </div>
        </div>
      </div> 
    </div><!--end row-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
          <div class="card radius-10 border-start border-0 border-4 border-info">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div>
                         <p class="mb-0 text-secondary">Total Courses</p>
                         <h4 class="my-1 text-info">{{ $totalCourses }}</h4>
                     </div>
                     <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-video'></i>
                     </div>
                 </div>
             </div>
          </div>
        </div>
        <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Categories</p>
                        <h4 class="my-1 text-danger">{{ $totalCategories }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-category'></i>
                    </div>
                </div>
            </div>
         </div>
       </div>
       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Sub Categories</p>
                        <h4 class="my-1 text-success">{{ $totalSubCategories }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-category-alt' ></i>
                    </div>
                </div>
            </div>
         </div>
       </div>
       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Reviews</p>
                        <h4 class="my-1 text-warning">{{ $totalReviews }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-comment-detail'></i>
                    </div>
                </div>
            </div>
         </div>
       </div> 
     </div><!--end row-->

     <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
          <div class="card radius-10 border-start border-0 border-4 border-info">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div>
                         <p class="mb-0 text-secondary">Total Questions</p>
                         <h4 class="my-1 text-info">{{ $totalQuestions }}</h4>
                     </div>
                     <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-help-circle'></i>
                     </div>
                 </div>
             </div>
          </div>
        </div>
        <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Blog Post</p>
                        <h4 class="my-1 text-danger">{{ $totalBlogPosts }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bx-news'></i>
                    </div>
                </div>
            </div>
         </div>
       </div>
       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Coupon Active</p>
                        <h4 class="my-1 text-success">{{ $totalCoupons }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-discount' ></i>
                    </div>
                </div>
            </div>
         </div>
       </div>
       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Wishlist</p>
                        <h4 class="my-1 text-warning">{{ $totalWishlists }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-heart'></i>
                    </div>
                </div>
            </div>
         </div>
       </div> 
     </div><!--end row-->

    <div class="row">
       <div class="col-12 col-lg-12 d-flex">
          <div class="card radius-10 w-100">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Sales Overview</h6>
                    </div>
                </div>
            </div>
              <div class="card-body">
                <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                    <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Sales</span>
                </div>
                <div class="chart-container-1">
                    <canvas id="chart1" data-months='{{ json_encode($orderMonths) }}' data-counts='{{ json_encode($orderCounts) }}'></canvas>
                  </div>
              </div>
          </div>
       </div>
     
    </div><!--end row-->

     <div class="card radius-10">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">Recent Orders</h6>
                </div>
                <div class="dropdown ms-auto">
                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:;">Action</a>
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Another action</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
             <div class="card-body">
             <div class="table-responsive">
               <table class="table align-middle mb-0">
                <thead class="table-light">
                 <tr>
                   <th>Course Name</th>
                   <th>Invoice</th>
                   <th>Amount</th>
                   <th>Date</th>
                 </tr>
                 </thead>
                 <tbody>
                    @foreach ($recentOrders as $order)
                    <tr>
                        <td>{{ $order->course_title }}</td>
                        <td>{{ $order->payment->invoice_no }}</td>
                        <td>${{ $order->price }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
              </div>
             </div>
        </div>



    

</div>
@endsection
